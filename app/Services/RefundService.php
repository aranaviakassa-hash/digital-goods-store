<?php

namespace App\Services;

use App\Models\FulfillmentAttempt;
use App\Models\Order;
use App\Models\PaymentAttempt;
use App\Models\Refund;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class RefundService
{
    public function __construct(
        protected AuditLogService $auditLogService
    ) {
    }

    public function requireRefund(
        Order $order,
        ?PaymentAttempt $paymentAttempt = null,
        ?string $reason = null,
        bool $cancelOrder = true
    ): Refund {
        return DB::transaction(function () use (
            $order,
            $paymentAttempt,
            $reason,
            $cancelOrder
        ) {
            /*
             * Canonical lock order:
             * 1. Order
             * 2. PaymentAttempt
             * 3. Refund
             */
            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($paymentAttempt) {
                $lockedAttempt = PaymentAttempt::query()
                    ->whereKey($paymentAttempt->id)
                    ->where(
                        'order_id',
                        $lockedOrder->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();
            } else {
                $lockedAttempt = PaymentAttempt::query()
                    ->where(
                        'order_id',
                        $lockedOrder->id
                    )
                    ->where('status', 'paid')
                    ->latest('id')
                    ->lockForUpdate()
                    ->first();
            }

            if (! $lockedAttempt) {
                throw new RuntimeException(
                    'A paid payment attempt is required before refund.'
                );
            }

            if ($lockedAttempt->status !== 'paid') {
                throw new RuntimeException(
                    'Only a paid payment attempt can be refunded.'
                );
            }

            $existing = Refund::query()
                ->where(
                    'payment_attempt_id',
                    $lockedAttempt->id
                )
                ->lockForUpdate()
                ->first();

            if ($existing) {
                return $existing->fresh();
            }

            /*
             * Never automatically refund while delivery could
             * already have happened or still be uncertain.
             */
            $unsafeFulfillmentExists =
                FulfillmentAttempt::query()
                    ->where(
                        'order_id',
                        $lockedOrder->id
                    )
                    ->whereIn(
                        'status',
                        [
                            'processing',
                            'unknown',
                            'fulfilled',
                        ]
                    )
                    ->exists();

            if ($unsafeFulfillmentExists) {
                throw new RuntimeException(
                    'Refund requires manual review because fulfillment has started or is uncertain.'
                );
            }

            $refund = Refund::create([
                'order_id' =>
                    $lockedOrder->id,

                'payment_attempt_id' =>
                    $lockedAttempt->id,

                'provider' =>
                    $lockedAttempt->provider,

                'status' => 'required',

                'idempotency_key' =>
                    'REF-' .
                    strtoupper(
                        Str::uuid()->toString()
                    ),

                /*
                 * Refund the captured payment snapshot,
                 * not a potentially changed order total.
                 */
                'amount' =>
                    $lockedAttempt->amount,

                'currency' =>
                    $lockedAttempt->currency,

                'reason' => $reason,
            ]);

            if ($cancelOrder) {
                $lockedOrder->update([
                    'payment_status' =>
                        'refund_pending',

                    'status' =>
                        'cancelled',

                    'fulfillment_status' =>
                        'blocked',
                ]);
            }

            $this->auditLogService->log(
                'refund.required',
                $lockedOrder,
                [
                    'refund_id' =>
                        $refund->id,

                    'payment_attempt_id' =>
                        $lockedAttempt->id,

                    'amount' =>
                        $refund->amount,

                    'currency' =>
                        $refund->currency,

                    'provider' =>
                        $refund->provider,

                    'cancel_order' =>
                        $cancelOrder,

                    'reason' =>
                        $reason,
                ]
            );

            return $refund->fresh();
        });
    }
}