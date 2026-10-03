<?php

namespace App\Services;

use App\Models\FulfillmentAttempt;
use App\Models\Order;
use App\Models\OrderEvidence;
use App\Models\PaymentAttempt;
use App\Models\SecurityReview;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SecurityReviewService
{
    private const HIGH_VALUE_THRESHOLD = 50.00;
    private const RAPID_ORDER_WINDOW_MINUTES = 30;
    private const FAILED_PAYMENT_WINDOW_HOURS = 24;

    public function __construct(
        protected AuditLogService $auditLogService,
        protected RefundService $refundService
    ) {
    }

    public function startReview(
        Order $order
    ): SecurityReview {
        return DB::transaction(function () use ($order) {
            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $lockedOrder->payment_status
                !== 'paid'
            ) {
                throw new RuntimeException(
                    'Only paid orders can enter security review.'
                );
            }

            if (
                $lockedOrder->status
                === 'cancelled'
            ) {
                throw new RuntimeException(
                    'Cancelled orders cannot enter security review.'
                );
            }

            $review =
                SecurityReview::query()
                    ->where(
                        'order_id',
                        $lockedOrder->id
                    )
                    ->lockForUpdate()
                    ->first();

            if (
                $review
                && $review->status !== 'pending'
            ) {
                return $review;
            }

            [$riskScore, $riskFlags] =
                $this->calculateRisk(
                    $lockedOrder
                );

            if (! $review) {
                $review =
                    SecurityReview::create([
                        'order_id' =>
                            $lockedOrder->id,

                        'status' =>
                            'pending',

                        'risk_score' =>
                            $riskScore,

                        'risk_flags' =>
                            $riskFlags,
                    ]);
            } else {
                $review->update([
                    'risk_score' =>
                        $riskScore,

                    'risk_flags' =>
                        $riskFlags,
                ]);
            }

            $lockedOrder->update([
                'fulfillment_status' =>
                    'security_review',
            ]);

            $this->auditLogService->log(
                'security_review.started',
                $lockedOrder,
                [
                    'security_review_id' =>
                        $review->id,

                    'risk_score' =>
                        $riskScore,

                    'risk_flags' =>
                        array_column(
                            $riskFlags,
                            'code'
                        ),
                ]
            );

            return $review->fresh();
        });
    }

    public function approve(
        SecurityReview $review,
        ?string $notes = null
    ): SecurityReview {
        return DB::transaction(function () use (
            $review,
            $notes
        ) {
            /*
             * Order first.
             */
            $order = Order::query()
                ->whereKey($review->order_id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedReview =
                SecurityReview::query()
                    ->whereKey($review->id)
                    ->where(
                        'order_id',
                        $order->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

            if (
                $lockedReview->status
                === 'approved'
            ) {
                return $lockedReview->fresh();
            }

            if (
                $lockedReview->status
                !== 'pending'
            ) {
                throw new RuntimeException(
                    'Only pending security reviews can be approved.'
                );
            }

            if (
                $order->payment_status
                !== 'paid'
            ) {
                throw new RuntimeException(
                    'A security review can only be approved for a paid order.'
                );
            }

            if (
                $order->status === 'cancelled'
                || in_array(
                    $order->payment_status,
                    [
                        'refund_pending',
                        'refunded',
                    ],
                    true
                )
            ) {
                throw new RuntimeException(
                    'Cancelled or refunded orders cannot be approved for fulfillment.'
                );
            }

            $lockedReview->update([
                'status' => 'approved',
                'review_notes' => $notes,
                'reviewed_at' => now(),
            ]);

            $order->update([
                'fulfillment_status' =>
                    'processing',
            ]);

            $this->auditLogService->log(
                'security_review.approved',
                $order,
                [
                    'security_review_id' =>
                        $lockedReview->id,

                    'risk_score' =>
                        $lockedReview
                            ->risk_score,

                    'notes' =>
                        $notes,
                ]
            );

            return $lockedReview->fresh();
        });
    }

    public function reject(
        SecurityReview $review,
        ?string $notes = null
    ): SecurityReview {
        return DB::transaction(function () use (
            $review,
            $notes
        ) {
            /*
             * Order first.
             */
            $order = Order::query()
                ->whereKey($review->order_id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedReview =
                SecurityReview::query()
                    ->whereKey($review->id)
                    ->where(
                        'order_id',
                        $order->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

            if (
                $lockedReview->status
                === 'rejected'
            ) {
                return $lockedReview->fresh();
            }

            if (
                $lockedReview->status
                !== 'pending'
            ) {
                throw new RuntimeException(
                    'Only pending security reviews can be rejected.'
                );
            }

            $fulfillmentStarted =
                FulfillmentAttempt::query()
                    ->where(
                        'order_id',
                        $order->id
                    )
                    ->exists();

            if ($fulfillmentStarted) {
                throw new RuntimeException(
                    'Security review cannot be rejected after fulfillment has started.'
                );
            }

            /*
             * Create refund requirement BEFORE finalising
             * the rejection state.
             *
             * If refund preparation fails, whole transaction
             * rolls back.
             */
            $refund = null;

            if ($order->payment_status === 'paid') {
                $refund =
                    $this->refundService
                        ->requireRefund(
                            $order,
                            null,
                            $notes
                                ?? 'Security review rejected.',
                            true
                        );
            } else {
                $order->update([
                    'status' => 'cancelled',
                    'fulfillment_status' =>
                        'blocked',
                ]);
            }

            $lockedReview->update([
                'status' => 'rejected',
                'review_notes' => $notes,
                'reviewed_at' => now(),
            ]);

            $this->auditLogService->log(
                'security_review.rejected',
                $order->fresh(),
                [
                    'security_review_id' =>
                        $lockedReview->id,

                    'risk_score' =>
                        $lockedReview
                            ->risk_score,

                    'notes' =>
                        $notes,

                    'refund_required' =>
                        $refund !== null,

                    'refund_id' =>
                        $refund?->id,
                ]
            );

            return $lockedReview->fresh();
        });
    }

    private function calculateRisk(
        Order $order
    ): array {
        $score = 0;
        $flags = [];

        $email = strtolower(
            trim(
                (string)
                $order->customer_email
            )
        );

        $now = now();

        $rapidWindow =
            $now->copy()->subMinutes(
                self::RAPID_ORDER_WINDOW_MINUTES
            );

        $failedPaymentWindow =
            $now->copy()->subHours(
                self::FAILED_PAYMENT_WINDOW_HOURS
            );

        $addFlag = function (
            string $code,
            int $points,
            string $reason
        ) use (
            &$score,
            &$flags
        ): void {
            $score += $points;

            $flags[] = [
                'code' => $code,
                'points' => $points,
                'reason' => $reason,
            ];
        };

        if (
            (float) $order->total
            >= self::HIGH_VALUE_THRESHOLD
        ) {
            $addFlag(
                'high_value_order',
                20,
                'Order total is at or above the high-value review threshold.'
            );
        }

        if (! $order->user_id) {
            $addFlag(
                'guest_checkout',
                10,
                'Order was placed without an authenticated customer account.'
            );
        }

        $rapidOrdersByEmail =
            Order::query()
                ->whereRaw(
                    'LOWER(customer_email) = ?',
                    [$email]
                )
                ->where(
                    'created_at',
                    '>=',
                    $rapidWindow
                )
                ->count();

        if ($rapidOrdersByEmail >= 3) {
            $addFlag(
                'rapid_orders_email',
                25,
                'Three or more orders were created for the same email within the rapid-order window.'
            );
        }

        $currentOrderFailedPayments =
            PaymentAttempt::query()
                ->where(
                    'order_id',
                    $order->id
                )
                ->where(
                    'status',
                    'failed'
                )
                ->count();

        if (
            $currentOrderFailedPayments >= 1
        ) {
            $addFlag(
                'failed_payment_attempt',
                15,
                'The current order has at least one failed payment attempt.'
            );
        }

        $failedPaymentsByEmail =
            PaymentAttempt::query()
                ->where(
                    'status',
                    'failed'
                )
                ->where(
                    'created_at',
                    '>=',
                    $failedPaymentWindow
                )
                ->whereIn(
                    'order_id',
                    Order::query()
                        ->select('id')
                        ->whereRaw(
                            'LOWER(customer_email) = ?',
                            [$email]
                        )
                )
                ->count();

        if (
            $failedPaymentsByEmail >= 2
        ) {
            $addFlag(
                'repeated_failed_payments_email',
                25,
                'Multiple failed payment attempts were recorded for the same customer email within 24 hours.'
            );
        }

        $evidence =
            OrderEvidence::query()
                ->where(
                    'order_id',
                    $order->id
                )
                ->first();

        $ipAddress =
            $evidence?->ip_address;

        if ($ipAddress) {
            $rapidOrdersByIp =
                OrderEvidence::query()
                    ->where(
                        'ip_address',
                        $ipAddress
                    )
                    ->where(
                        'created_at',
                        '>=',
                        $rapidWindow
                    )
                    ->count();

            if ($rapidOrdersByIp >= 3) {
                $addFlag(
                    'rapid_orders_ip',
                    20,
                    'Three or more orders were recorded from the same IP address within the rapid-order window.'
                );
            }

            $failedPaymentsByIp =
                PaymentAttempt::query()
                    ->where(
                        'status',
                        'failed'
                    )
                    ->where(
                        'created_at',
                        '>=',
                        $failedPaymentWindow
                    )
                    ->whereIn(
                        'order_id',
                        OrderEvidence::query()
                            ->select('order_id')
                            ->where(
                                'ip_address',
                                $ipAddress
                            )
                    )
                    ->count();

            if (
                $failedPaymentsByIp >= 2
            ) {
                $addFlag(
                    'repeated_failed_payments_ip',
                    20,
                    'Multiple failed payment attempts were associated with the same IP address within 24 hours.'
                );
            }
        }

        return [
            min($score, 100),
            $flags,
        ];
    }
}