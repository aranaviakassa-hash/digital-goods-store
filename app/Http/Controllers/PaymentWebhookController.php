<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentAttempt;
use App\Models\WebhookEvent;
use App\Services\AuditLogService;
use App\Services\OrderService;
use App\Services\RefundService;
use App\Services\SecurityReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PaymentWebhookController extends Controller
{
    public function handle(
        Request $request,
        OrderService $orderService,
        SecurityReviewService $securityReviewService,
        RefundService $refundService,
        AuditLogService $auditLogService
    ): JsonResponse {
        $rawBody = $request->getContent();
        $bodyHash = hash('sha256', $rawBody);

        $expectedSecret = (string) config('payments.webhook_secret');
        $providedSecret = (string) $request->header('X-Webhook-Secret');

        if (
            $expectedSecret === ''
            || ! hash_equals($expectedSecret, $providedSecret)
        ) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'provider' => ['required', 'string', 'max:50'],
            'merchant_reference' => ['required', 'string', 'max:255'],
            'provider_payment_id' => ['required', 'string', 'max:255'],
            'amount' => [
                'required',
                'decimal:0,2',
                'regex:/^\d{1,8}(?:\.\d{1,2})?$/',
            ],
            'currency' => ['required', 'string', 'size:3'],
            'status' => ['required', 'in:paid,failed'],
        ]);

        $validated['currency'] = strtoupper($validated['currency']);

        $eventKey = hash(
            'sha256',
            implode('|', [
                $validated['provider'],
                $validated['merchant_reference'],
                $validated['provider_payment_id'],
                $validated['status'],
                (string) $validated['amount'],
                $validated['currency'],
                $bodyHash,
            ])
        );

        $event = WebhookEvent::firstOrCreate(
            [
                'provider' => $validated['provider'],
                'event_key' => $eventKey,
            ],
            [
                'provider_payment_id' => $validated['provider_payment_id'],
                'merchant_reference' => $validated['merchant_reference'],
                'event_type' => $validated['status'],
                'processing_status' => 'received',
                'payload' => $validated,
                'body_hash' => $bodyHash,
                'headers_hash' => hash(
                    'sha256',
                    json_encode([
                        'content-type' => $request->header('Content-Type'),
                        'user-agent' => $request->header('User-Agent'),
                    ], JSON_UNESCAPED_SLASHES)
                ),
                'received_at' => now(),
            ]
        );

        if (
            ! $event->wasRecentlyCreated
            && $event->processing_status === 'processed'
        ) {
            return response()->json(['message' => 'Already processed']);
        }

        $attempt = PaymentAttempt::query()
            ->where('provider', $validated['provider'])
            ->where('merchant_reference', $validated['merchant_reference'])
            ->first();

        if (! $attempt) {
            $event->update([
                'processing_status' => 'rejected',
                'processing_result' => 'Unknown merchant reference.',
                'processed_at' => now(),
            ]);

            return response()->json(
                ['message' => 'Payment attempt not found.'],
                422
            );
        }

        if (
            $this->minorUnits($validated['amount'])
                !== $this->minorUnits($attempt->amount)
            || $validated['currency'] !== strtoupper($attempt->currency)
        ) {
            $event->update([
                'processing_status' => 'rejected',
                'processing_result' => 'Amount or currency mismatch.',
                'processed_at' => now(),
            ]);

            $auditLogService->log(
                'payment.webhook.amount_mismatch',
                $attempt->order,
                [
                    'payment_attempt_id' => $attempt->id,
                    'provider_payment_id' => $validated['provider_payment_id'],
                    'expected_amount' => $attempt->amount,
                    'received_amount' => $validated['amount'],
                    'expected_currency' => $attempt->currency,
                    'received_currency' => $validated['currency'],
                ]
            );

            return response()->json(
                ['message' => 'Payment amount or currency mismatch.'],
                422
            );
        }

        $result = DB::transaction(function () use (
            $validated,
            $attempt,
            $event,
            $orderService,
            $securityReviewService,
            $refundService,
            $auditLogService
        ) {
            $order = Order::query()
                ->whereKey($attempt->order_id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedAttempt = PaymentAttempt::query()
                ->whereKey($attempt->id)
                ->where('order_id', $order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                filled($lockedAttempt->provider_payment_id)
                && $lockedAttempt->provider_payment_id
                    !== $validated['provider_payment_id']
            ) {
                $event->update([
                    'processing_status' => 'rejected',
                    'processing_result' => 'Provider payment reference mismatch.',
                    'processed_at' => now(),
                ]);

                return [
                    'message' => 'Payment reference mismatch.',
                    'status' => 409,
                ];
            }

            if ($validated['status'] === 'failed') {
                if ($lockedAttempt->status === 'paid') {
                    $event->update([
                        'processing_status' => 'processed',
                        'processing_result' => 'Paid payment was not downgraded.',
                        'processed_at' => now(),
                    ]);

                    return [
                        'message' => 'Paid payment cannot be downgraded.',
                        'status' => 200,
                    ];
                }

                $orderService->markPaymentFailed(
                    $lockedAttempt,
                    [
                        'source' => 'local_webhook',
                        'status' => 'failed',
                    ]
                );

                $auditLogService->log(
                    'payment.webhook.failed',
                    $order,
                    [
                        'payment_attempt_id' => $lockedAttempt->id,
                        'provider' => $validated['provider'],
                        'provider_payment_id' => $validated['provider_payment_id'],
                    ]
                );

                $event->update([
                    'processing_status' => 'processed',
                    'processing_result' => 'Failure recorded.',
                    'processed_at' => now(),
                ]);

                return [
                    'message' => 'Failure recorded',
                    'status' => 200,
                ];
            }

            /*
             * A provider may replay the same successful capture with
             * byte-different JSON, producing a distinct WebhookEvent key.
             * Once this exact PaymentAttempt is already paid, never rewrite
             * the order back to processing/security_review. Record the event
             * as harmless and leave the current order state untouched.
             */
            if ($lockedAttempt->status === 'paid') {
                $event->update([
                    'processing_status' => 'processed',
                    'processing_result' => 'Paid capture replay ignored; order state unchanged.',
                    'processed_at' => now(),
                ]);

                return [
                    'message' => 'Paid capture already recorded.',
                    'status' => 200,
                ];
            }

            $orderService->markPaymentPaid(
                $lockedAttempt,
                $validated['provider_payment_id'],
                [
                    'source' => 'local_webhook',
                    'status' => 'paid',
                ]
            );

            $lockedAttempt->refresh();
            $order->refresh();

            $anotherPaidAttempt = PaymentAttempt::query()
                ->where('order_id', $order->id)
                ->where('status', 'paid')
                ->where('id', '!=', $lockedAttempt->id)
                ->exists();

            if ($anotherPaidAttempt) {
                $refundService->requireRefund(
                    $order,
                    $lockedAttempt,
                    'Duplicate payment captured for the same order.',
                    false
                );

                $event->update([
                    'processing_status' => 'processed',
                    'processing_result' => 'Duplicate payment captured; refund required.',
                    'processed_at' => now(),
                ]);

                return [
                    'message' => 'Duplicate payment recorded for refund.',
                    'status' => 200,
                ];
            }

            if (
                $order->status === 'cancelled'
                || in_array(
                    $order->payment_status,
                    ['refund_pending', 'refunded'],
                    true
                )
            ) {
                $refundService->requireRefund(
                    $order,
                    $lockedAttempt,
                    'Payment received after order became non-payable.',
                    true
                );

                $event->update([
                    'processing_status' => 'processed',
                    'processing_result' => 'Late payment captured; refund required.',
                    'processed_at' => now(),
                ]);

                return [
                    'message' => 'Late payment recorded for refund.',
                    'status' => 200,
                ];
            }

            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'fulfillment_status' => 'security_review',
                'payment_reference' => $validated['provider_payment_id'],
            ]);

            $auditLogService->log(
                'payment.webhook.paid',
                $order,
                [
                    'payment_attempt_id' => $lockedAttempt->id,
                    'provider' => $validated['provider'],
                    'provider_payment_id' => $validated['provider_payment_id'],
                ]
            );

            $securityReviewService->startReview($order->fresh());

            $event->update([
                'processing_status' => 'processed',
                'processing_result' => 'Payment processed.',
                'processed_at' => now(),
            ]);

            return [
                'message' => 'Payment processed',
                'status' => 200,
            ];
        });

        return response()->json(
            ['message' => $result['message']],
            $result['status']
        );
    }

    private function minorUnits(mixed $amount): int
    {
        $value = trim((string) $amount);

        if (! preg_match('/^\d{1,8}(?:\.\d{1,2})?$/', $value)) {
            throw new InvalidArgumentException(
                'Invalid monetary amount format.'
            );
        }

        [$whole, $fraction] = array_pad(
            explode('.', $value, 2),
            2,
            ''
        );

        $fraction = str_pad($fraction, 2, '0', STR_PAD_RIGHT);

        return ((int) $whole * 100) + (int) $fraction;
    }
}
