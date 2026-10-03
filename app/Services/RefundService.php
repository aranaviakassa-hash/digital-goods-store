<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Refund;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RefundService
{
    public function __construct(
        protected AuditLogService $auditLogService
    ) {
    }

    public function requireRefund(
        Order $order,
        ?string $reason = null
    ): Refund {
        return DB::transaction(function () use ($order, $reason) {
            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                ! in_array(
                    $lockedOrder->payment_status,
                    ['paid', 'refund_pending'],
                    true
                )
            ) {
                throw new RuntimeException(
                    'Only paid orders can require a refund.'
                );
            }

            $existing = Refund::query()
                ->where('order_id', $lockedOrder->id)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                return $existing->fresh();
            }

            $paymentAttempt = $lockedOrder
                ->paymentAttempts()
                ->where('status', 'paid')
                ->latest('id')
                ->first();

            $refund = Refund::create([
                'order_id' => $lockedOrder->id,
                'payment_attempt_id' => $paymentAttempt?->id,
                'provider' => $paymentAttempt?->provider
                    ?? $lockedOrder->payment_provider,
                'status' => 'required',
                'amount' => $lockedOrder->total,
                'currency' => $lockedOrder->currency,
                'reason' => $reason,
                'requested_at' => now(),
            ]);

            $lockedOrder->update([
                'payment_status' => 'refund_pending',
                'status' => 'cancelled',
                'fulfillment_status' => 'failed',
            ]);

            $this->auditLogService->log(
                'refund.required',
                $lockedOrder,
                [
                    'refund_id' => $refund->id,
                    'amount' => $refund->amount,
                    'currency' => $refund->currency,
                    'provider' => $refund->provider,
                    'reason' => $reason,
                ]
            );

            return $refund->fresh();
        });
    }
}