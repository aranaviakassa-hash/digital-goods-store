<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentAttempt;
use App\Services\AuditLogService;
use App\Services\OrderService;
use App\Services\SecurityReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentWebhookController extends Controller
{
    public function handle(
        Request $request,
        OrderService $orderService,
        SecurityReviewService $securityReviewService,
        AuditLogService $auditLogService
    ): JsonResponse {
        $expectedSecret = (string) config(
            'payments.webhook_secret'
        );

        $providedSecret = (string) $request->header(
            'X-Webhook-Secret'
        );

        if (
            $expectedSecret === ''
            || ! hash_equals(
                $expectedSecret,
                $providedSecret
            )
        ) {
            return response()->json(
                ['message' => 'Unauthorized'],
                401
            );
        }

        $validated = $request->validate([
            'order_number' => [
                'required',
                'string',
                'max:100',
            ],

            'provider' => [
                'required',
                'string',
                'max:50',
            ],

            'provider_payment_id' => [
                'required',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'in:paid,failed',
            ],
        ]);

        $result = DB::transaction(function () use (
            $validated,
            $orderService,
            $securityReviewService,
            $auditLogService
        ) {
            $order = Order::query()
                ->where(
                    'order_number',
                    $validated['order_number']
                )
                ->lockForUpdate()
                ->firstOrFail();

            $attempt = PaymentAttempt::query()
                ->where('order_id', $order->id)
                ->where(
                    'provider',
                    $validated['provider']
                )
                ->where(function ($query) use ($validated) {
                    $query
                        ->where(
                            'provider_payment_id',
                            $validated['provider_payment_id']
                        )
                        ->orWhereNull(
                            'provider_payment_id'
                        );
                })
                ->latest('id')
                ->lockForUpdate()
                ->first();

            if (! $attempt) {
                abort(
                    422,
                    'Matching payment attempt not found.'
                );
            }

            if (
                filled($attempt->provider_payment_id)
                && $attempt->provider_payment_id
                    !== $validated['provider_payment_id']
            ) {
                abort(
                    409,
                    'Payment reference mismatch.'
                );
            }

            if (! $attempt->provider_payment_id) {
                $attempt->update([
                    'provider_payment_id' =>
                        $validated['provider_payment_id'],
                ]);
            }

            if ($validated['status'] === 'paid') {
                if (
                    $attempt->status === 'paid'
                    || $order->payment_status === 'paid'
                ) {
                    return [
                        'message' => 'Already processed',
                        'status' => 200,
                    ];
                }

                $orderService->markPaymentPaid(
                    $attempt,
                    $validated['provider_payment_id'],
                    [
                        'source' => 'local_webhook',
                        'status' => 'paid',
                    ]
                );

                $auditLogService->log(
                    'payment.webhook.paid',
                    $order,
                    [
                        'provider' =>
                            $validated['provider'],

                        'provider_payment_id' =>
                            $validated['provider_payment_id'],
                    ]
                );

                $securityReviewService->startReview(
                    $order->fresh()
                );

                return [
                    'message' => 'Payment processed',
                    'status' => 200,
                ];
            }

            if (
                $order->payment_status === 'paid'
                || $attempt->status === 'paid'
            ) {
                return [
                    'message' =>
                        'Paid payment cannot be downgraded.',
                    'status' => 200,
                ];
            }

            if ($attempt->status === 'failed') {
                return [
                    'message' => 'Already processed',
                    'status' => 200,
                ];
            }

            $orderService->markPaymentFailed(
                $attempt,
                [
                    'source' => 'local_webhook',
                    'status' => 'failed',
                ]
            );

            $auditLogService->log(
                'payment.webhook.failed',
                $order,
                [
                    'provider' =>
                        $validated['provider'],

                    'provider_payment_id' =>
                        $validated['provider_payment_id'],
                ]
            );

            return [
                'message' => 'Failure recorded',
                'status' => 200,
            ];
        });

        return response()->json(
            ['message' => $result['message']],
            $result['status']
        );
    }
}