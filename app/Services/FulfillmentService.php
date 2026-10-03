<?php

namespace App\Services;

use App\Contracts\SupplierAdapter;
use App\Models\FulfillmentAttempt;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class FulfillmentService
{
    public function __construct(
        protected AuditLogService $auditLogService
    ) {
    }

    public function fulfill(
        Order $order,
        SupplierAdapter $supplier,
        string $supplierName = 'reloadly'
    ): Order {
        $attempts = $this->reserveAttempts(
            $order,
            $supplierName
        );

        foreach ($attempts as $attempt) {
            if ($attempt->status === 'fulfilled') {
                continue;
            }

            if ($attempt->status !== 'reserved') {
                throw new RuntimeException(
                    'Fulfillment attempt requires manual review before retry.'
                );
            }

            $item = OrderItem::query()
                ->findOrFail($attempt->order_item_id);

            $this->markProcessing(
                $order,
                $attempt
            );

            try {
                /*
                 * External supplier call is intentionally outside
                 * every database transaction.
                 */
                $result = $supplier->fulfill(
                    $order->fresh(),
                    $item,
                    $attempt->idempotency_key
                );

                $supplierReference =
                    $result['supplier_reference'] ?? null;

                $response =
                    $result['response'] ?? $result;

                DB::transaction(function () use (
                    $order,
                    $attempt,
                    $supplierName,
                    $supplierReference,
                    $response
                ) {
                    $lockedOrder = Order::query()
                        ->whereKey($order->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $lockedAttempt = FulfillmentAttempt::query()
                        ->whereKey($attempt->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($lockedAttempt->status === 'fulfilled') {
                        return;
                    }

                    if ($lockedAttempt->status !== 'processing') {
                        throw new RuntimeException(
                            'Fulfillment result arrived for an attempt requiring manual review.'
                        );
                    }

                    $lockedAttempt->update([
                        'status' => 'fulfilled',
                        'supplier_reference' =>
                            $supplierReference,
                        'response_payload' =>
                            $response,
                        'fulfilled_at' => now(),
                        'failed_at' => null,
                    ]);

                    $this->auditLogService->log(
                        'fulfillment.item.completed',
                        $lockedOrder,
                        [
                            'fulfillment_attempt_id' =>
                                $lockedAttempt->id,
                            'order_item_id' =>
                                $lockedAttempt->order_item_id,
                            'supplier' =>
                                $supplierName,
                            'supplier_reference' =>
                                $supplierReference,
                        ]
                    );
                });
            } catch (Throwable $exception) {
                DB::transaction(function () use (
                    $order,
                    $attempt,
                    $supplierName,
                    $exception
                ) {
                    $lockedOrder = Order::query()
                        ->whereKey($order->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $lockedAttempt = FulfillmentAttempt::query()
                        ->whereKey($attempt->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($lockedAttempt->status !== 'fulfilled') {
                        $lockedAttempt->update([
                            'status' => 'unknown',
                            'failed_at' => now(),
                            'response_payload' => [
                                'error' =>
                                    $exception->getMessage(),
                                'manual_review_required' =>
                                    true,
                            ],
                        ]);
                    }

                    FulfillmentAttempt::query()
                        ->where('order_id', $lockedOrder->id)
                        ->where('status', 'reserved')
                        ->update([
                            'status' => 'blocked',
                        ]);

                    $lockedOrder->update([
                        'fulfillment_status' =>
                            'manual_review',
                    ]);

                    $this->auditLogService->log(
                        'fulfillment.item.unknown',
                        $lockedOrder,
                        [
                            'fulfillment_attempt_id' =>
                                $lockedAttempt->id,
                            'order_item_id' =>
                                $lockedAttempt->order_item_id,
                            'supplier' =>
                                $supplierName,
                            'error' =>
                                $exception->getMessage(),
                            'manual_review_required' =>
                                true,
                        ]
                    );
                });

                throw $exception;
            }
        }

        return $this->finalizeOrder(
            $order,
            $supplierName
        );
    }

    public function reconcileStaleProcessing(
        int $staleMinutes = 10
    ): int {
        if ($staleMinutes < 1) {
            throw new RuntimeException(
                'Stale fulfillment threshold must be at least one minute.'
            );
        }

        $threshold = now()->subMinutes(
            $staleMinutes
        );

        $candidates = FulfillmentAttempt::query()
            ->where('status', 'processing')
            ->whereNotNull('started_at')
            ->where(
                'started_at',
                '<=',
                $threshold
            )
            ->orderBy('id')
            ->get([
                'id',
                'order_id',
            ]);

        $reconciled = 0;

        foreach ($candidates as $candidate) {
            $changed = DB::transaction(function () use (
                $candidate,
                $threshold
            ) {
                /*
                 * Canonical lock order:
                 * Order first, then FulfillmentAttempt.
                 */
                $lockedOrder = Order::query()
                    ->whereKey($candidate->order_id)
                    ->lockForUpdate()
                    ->first();

                if (! $lockedOrder) {
                    return false;
                }

                $lockedAttempt = FulfillmentAttempt::query()
                    ->whereKey($candidate->id)
                    ->lockForUpdate()
                    ->first();

                if (! $lockedAttempt) {
                    return false;
                }

                if (
                    $lockedAttempt->status !== 'processing'
                    || ! $lockedAttempt->started_at
                    || $lockedAttempt->started_at->gt(
                        $threshold
                    )
                ) {
                    return false;
                }

                $lockedAttempt->update([
                    'status' => 'unknown',
                    'failed_at' => now(),
                    'response_payload' => [
                        'error' =>
                            'Stale processing attempt detected.',
                        'reason' =>
                            'stale_processing_timeout',
                        'manual_review_required' =>
                            true,
                    ],
                ]);

                FulfillmentAttempt::query()
                    ->where(
                        'order_id',
                        $lockedOrder->id
                    )
                    ->where('status', 'reserved')
                    ->update([
                        'status' => 'blocked',
                    ]);

                $lockedOrder->update([
                    'fulfillment_status' =>
                        'manual_review',
                ]);

                $this->auditLogService->log(
                    'fulfillment.stale_processing',
                    $lockedOrder,
                    [
                        'fulfillment_attempt_id' =>
                            $lockedAttempt->id,
                        'order_item_id' =>
                            $lockedAttempt->order_item_id,
                        'started_at' =>
                            $lockedAttempt->started_at
                                ?->toIso8601String(),
                        'manual_review_required' =>
                            true,
                    ]
                );

                return true;
            });

            if ($changed) {
                $reconciled++;
            }
        }

        return $reconciled;
    }

    private function markProcessing(
        Order $order,
        FulfillmentAttempt $attempt
    ): void {
        DB::transaction(function () use (
            $order,
            $attempt
        ) {
            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $lockedOrder->payment_status !== 'paid'
                || $lockedOrder->fulfillment_status
                    !== 'processing'
            ) {
                throw new RuntimeException(
                    'Order is no longer eligible for fulfillment.'
                );
            }

            $lockedAttempt = FulfillmentAttempt::query()
                ->whereKey($attempt->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedAttempt->status !== 'reserved') {
                throw new RuntimeException(
                    'Fulfillment attempt requires manual review before retry.'
                );
            }

            $lockedAttempt->update([
                'status' => 'processing',
                'started_at' => now(),
                'failed_at' => null,
            ]);
        });
    }

    private function reserveAttempts(
        Order $order,
        string $supplierName
    ): array {
        return DB::transaction(function () use (
            $order,
            $supplierName
        ) {
            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->payment_status !== 'paid') {
                throw new RuntimeException(
                    'Order must be paid before fulfillment.'
                );
            }

            if (
                $lockedOrder->fulfillment_status
                !== 'processing'
            ) {
                throw new RuntimeException(
                    'Order must pass security review before fulfillment.'
                );
            }

            $items = $lockedOrder
                ->items()
                ->orderBy('id')
                ->get();

            if ($items->isEmpty()) {
                throw new RuntimeException(
                    'Order has no items to fulfill.'
                );
            }

            $attempts = [];

            foreach ($items as $item) {
                $idempotencyKey =
                    $this->makeIdempotencyKey(
                        $lockedOrder->id,
                        $item->id,
                        $supplierName
                    );

                $attempt = FulfillmentAttempt::query()
                    ->where(
                        'supplier',
                        $supplierName
                    )
                    ->where(
                        'order_item_id',
                        $item->id
                    )
                    ->first();

                if (! $attempt) {
                    $attempt = FulfillmentAttempt::create([
                        'order_id' =>
                            $lockedOrder->id,
                        'order_item_id' =>
                            $item->id,
                        'supplier' =>
                            $supplierName,
                        'status' =>
                            'reserved',
                        'idempotency_key' =>
                            $idempotencyKey,
                        'request_payload' => [
                            'order_item_id' =>
                                $item->id,
                            'product_id' =>
                                $item->product_id,
                            'product_code' =>
                                $item->product_code,
                            'quantity' =>
                                $item->quantity,
                            'delivery_data' =>
                                $item->delivery_data,
                        ],
                    ]);
                }

                if (
                    ! in_array(
                        $attempt->status,
                        [
                            'reserved',
                            'fulfilled',
                        ],
                        true
                    )
                ) {
                    throw new RuntimeException(
                        'Existing fulfillment attempt requires manual review.'
                    );
                }

                $attempts[] = $attempt;
            }

            return $attempts;
        });
    }

    private function finalizeOrder(
        Order $order,
        string $supplierName
    ): Order {
        return DB::transaction(function () use (
            $order,
            $supplierName
        ) {
            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            $attempts = FulfillmentAttempt::query()
                ->where(
                    'order_id',
                    $lockedOrder->id
                )
                ->where(
                    'supplier',
                    $supplierName
                )
                ->orderBy('id')
                ->get();

            if ($attempts->isEmpty()) {
                throw new RuntimeException(
                    'No fulfillment attempts exist for order.'
                );
            }

            if (
                $attempts->contains(
                    fn (FulfillmentAttempt $attempt) =>
                        $attempt->status
                        !== 'fulfilled'
                )
            ) {
                throw new RuntimeException(
                    'Order is not fully fulfilled.'
                );
            }

            $supplierReferences = $attempts
                ->pluck('supplier_reference')
                ->filter()
                ->values();

            $lockedOrder->update([
                'status' => 'completed',
                'fulfillment_status' =>
                    'fulfilled',
                'supplier_reference' =>
                    $supplierReferences->count() === 1
                        ? $supplierReferences->first()
                        : null,
            ]);

            $this->auditLogService->log(
                'fulfillment.completed',
                $lockedOrder,
                [
                    'supplier' =>
                        $supplierName,
                    'attempt_ids' =>
                        $attempts
                            ->pluck('id')
                            ->all(),
                    'supplier_references' =>
                        $supplierReferences
                            ->all(),
                ]
            );

            return $lockedOrder->fresh();
        });
    }

    private function makeIdempotencyKey(
        int $orderId,
        int $orderItemId,
        string $supplierName
    ): string {
        return 'FUL-' .
            strtoupper(
                substr(
                    hash(
                        'sha256',
                        $supplierName
                        . ':'
                        . $orderId
                        . ':'
                        . $orderItemId
                    ),
                    0,
                    40
                )
            );
    }
}