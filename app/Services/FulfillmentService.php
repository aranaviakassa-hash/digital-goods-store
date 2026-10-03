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

            DB::transaction(function () use ($attempt) {
                FulfillmentAttempt::query()
                    ->whereKey($attempt->id)
                    ->lockForUpdate()
                    ->firstOrFail()
                    ->update([
                        'status' => 'processing',
                        'failed_at' => null,
                    ]);
            });

            try {
                /*
                 * IMPORTANT:
                 * External supplier call is intentionally OUTSIDE
                 * any database transaction.
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
                    $attempt,
                    $order,
                    $supplierName,
                    $supplierReference,
                    $response
                ) {
                    $lockedAttempt =
                        FulfillmentAttempt::query()
                            ->whereKey($attempt->id)
                            ->lockForUpdate()
                            ->firstOrFail();

                    if ($lockedAttempt->status === 'fulfilled') {
                        return;
                    }

                    $lockedAttempt->update([
                        'status' => 'fulfilled',
                        'supplier_reference' =>
                            $supplierReference,
                        'response_payload' => $response,
                        'fulfilled_at' => now(),
                        'failed_at' => null,
                    ]);

                    $this->auditLogService->log(
                        'fulfillment.item.completed',
                        $order,
                        [
                            'fulfillment_attempt_id' =>
                                $lockedAttempt->id,
                            'order_item_id' =>
                                $lockedAttempt->order_item_id,
                            'supplier' => $supplierName,
                            'supplier_reference' =>
                                $supplierReference,
                        ]
                    );
                });
            } catch (Throwable $exception) {
                DB::transaction(function () use (
                    $attempt,
                    $order,
                    $supplierName,
                    $exception
                ) {
                    $lockedAttempt =
                        FulfillmentAttempt::query()
                            ->whereKey($attempt->id)
                            ->lockForUpdate()
                            ->firstOrFail();

                    if ($lockedAttempt->status !== 'fulfilled') {
                        /*
                         * We intentionally use "unknown" rather than
                         * automatically retrying.
                         *
                         * A supplier timeout may happen AFTER the
                         * external provider has already delivered
                         * the digital product.
                         */
                        $lockedAttempt->update([
                            'status' => 'unknown',
                            'failed_at' => now(),
                            'response_payload' => [
                                'error' =>
                                    $exception->getMessage(),
                            ],
                        ]);
                    }

                    FulfillmentAttempt::query()
                        ->where('order_id', $order->id)
                        ->where('status', 'reserved')
                        ->update([
                            'status' => 'blocked',
                        ]);

                    $order->update([
                        'fulfillment_status' =>
                            'manual_review',
                    ]);

                    $this->auditLogService->log(
                        'fulfillment.item.unknown',
                        $order,
                        [
                            'fulfillment_attempt_id' =>
                                $lockedAttempt->id,
                            'order_item_id' =>
                                $lockedAttempt->order_item_id,
                            'supplier' => $supplierName,
                            'error' =>
                                $exception->getMessage(),
                            'manual_review_required' => true,
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

                $attempt =
                    FulfillmentAttempt::query()
                        ->where('supplier', $supplierName)
                        ->where('order_item_id', $item->id)
                        ->first();

                if (! $attempt) {
                    $attempt =
                        FulfillmentAttempt::create([
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
                ->where('order_id', $lockedOrder->id)
                ->where('supplier', $supplierName)
                ->get();

            if ($attempts->isEmpty()) {
                throw new RuntimeException(
                    'No fulfillment attempts exist for order.'
                );
            }

            if (
                $attempts->contains(
                    fn (FulfillmentAttempt $attempt) =>
                        $attempt->status !== 'fulfilled'
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
                'fulfillment_status' => 'fulfilled',
                'supplier_reference' =>
                    $supplierReferences->count() === 1
                        ? $supplierReferences->first()
                        : null,
            ]);

            $this->auditLogService->log(
                'fulfillment.completed',
                $lockedOrder,
                [
                    'supplier' => $supplierName,
                    'attempt_ids' =>
                        $attempts->pluck('id')->all(),
                    'supplier_references' =>
                        $supplierReferences->all(),
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
                        $supplierName .
                        ':' .
                        $orderId .
                        ':' .
                        $orderItemId
                    ),
                    0,
                    40
                )
            );
    }
}