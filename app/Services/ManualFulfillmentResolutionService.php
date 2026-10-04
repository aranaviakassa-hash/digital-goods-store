<?php

namespace App\Services;

use App\Models\FulfillmentAttempt;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ManualFulfillmentResolutionService
{
    public function __construct(
        protected AuditLogService $auditLogService
    ) {
    }

    public function resolveAsFulfilled(
        FulfillmentAttempt $attempt,
        string $supplierReference,
        string $notes
    ): FulfillmentAttempt {
        return DB::transaction(function () use (
            $attempt,
            $supplierReference,
            $notes
        ) {
            $lockedOrder = Order::query()
                ->whereKey($attempt->order_id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedAttempt = FulfillmentAttempt::query()
                ->whereKey($attempt->id)
                ->where('order_id', $lockedOrder->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedAttempt->status !== 'unknown') {
                throw new RuntimeException(
                    'Only unknown fulfillment attempts can be manually resolved.'
                );
            }

            $payload = is_array($lockedAttempt->response_payload)
                ? $lockedAttempt->response_payload
                : [];

            $payload['manual_resolution'] = [
                'outcome' => 'fulfilled',
                'notes' => trim($notes),
                'resolved_at' => now()->toIso8601String(),
            ];

            $lockedAttempt->update([
                'status' => 'fulfilled',
                'supplier_reference' => trim($supplierReference),
                'response_payload' => $payload,
                'fulfilled_at' => now(),
                'failed_at' => null,
            ]);

            FulfillmentAttempt::query()
                ->where('order_id', $lockedOrder->id)
                ->where('status', 'blocked')
                ->update([
                    'status' => 'reserved',
                ]);

            $remaining = FulfillmentAttempt::query()
                ->where('order_id', $lockedOrder->id)
                ->where('status', '!=', 'fulfilled')
                ->exists();

            if ($remaining) {
                $lockedOrder->update([
                    'status' => 'processing',
                    'fulfillment_status' => 'processing',
                ]);
            } else {
                $references = FulfillmentAttempt::query()
                    ->where('order_id', $lockedOrder->id)
                    ->pluck('supplier_reference')
                    ->filter()
                    ->values();

                $lockedOrder->update([
                    'status' => 'completed',
                    'fulfillment_status' => 'fulfilled',
                    'supplier_reference' => $references->count() === 1
                        ? $references->first()
                        : null,
                ]);
            }

            $this->auditLogService->log(
                'fulfillment.manual_resolved_fulfilled',
                $lockedOrder,
                [
                    'fulfillment_attempt_id' => $lockedAttempt->id,
                    'order_item_id' => $lockedAttempt->order_item_id,
                    'supplier' => $lockedAttempt->supplier,
                    'supplier_reference' => $lockedAttempt->supplier_reference,
                    'notes' => trim($notes),
                ]
            );

            return $lockedAttempt->fresh();
        });
    }

    public function resolveAsFailed(
        FulfillmentAttempt $attempt,
        string $notes
    ): FulfillmentAttempt {
        return DB::transaction(function () use (
            $attempt,
            $notes
        ) {
            $lockedOrder = Order::query()
                ->whereKey($attempt->order_id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedAttempt = FulfillmentAttempt::query()
                ->whereKey($attempt->id)
                ->where('order_id', $lockedOrder->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedAttempt->status !== 'unknown') {
                throw new RuntimeException(
                    'Only unknown fulfillment attempts can be manually resolved.'
                );
            }

            $payload = is_array($lockedAttempt->response_payload)
                ? $lockedAttempt->response_payload
                : [];

            $payload['manual_resolution'] = [
                'outcome' => 'failed',
                'notes' => trim($notes),
                'resolved_at' => now()->toIso8601String(),
            ];

            $lockedAttempt->update([
                'status' => 'failed',
                'response_payload' => $payload,
                'failed_at' => now(),
            ]);

            $lockedOrder->update([
                'fulfillment_status' => 'manual_review',
            ]);

            $this->auditLogService->log(
                'fulfillment.manual_resolved_failed',
                $lockedOrder,
                [
                    'fulfillment_attempt_id' => $lockedAttempt->id,
                    'order_item_id' => $lockedAttempt->order_item_id,
                    'supplier' => $lockedAttempt->supplier,
                    'notes' => trim($notes),
                    'next_action' => 'Manual refund or alternate-supplier decision required.',
                ]
            );

            return $lockedAttempt->fresh();
        });
    }
}
