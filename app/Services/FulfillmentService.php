<?php

namespace App\Services;

use App\Contracts\SupplierAdapter;
use App\Models\FulfillmentAttempt;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        string $supplierName = 'reloadly',
        ?string $idempotencyKey = null
    ): Order {
        if ($order->payment_status !== 'paid') {
            throw new RuntimeException(
                'Order must be paid before fulfillment.'
            );
        }

        if ($order->fulfillment_status !== 'processing') {
            throw new RuntimeException(
                'Order must pass security review before fulfillment.'
            );
        }

        $idempotencyKey ??=
            'FUL-' . strtoupper(Str::uuid()->toString());

        $existing = FulfillmentAttempt::query()
            ->where('idempotency_key', $idempotencyKey)
            ->first();

        if ($existing) {
            return $order->fresh();
        }

        return DB::transaction(function () use (
            $order,
            $supplier,
            $supplierName,
            $idempotencyKey
        ) {
            $attempt = FulfillmentAttempt::create([
                'order_id' => $order->id,
                'supplier' => $supplierName,
                'status' => 'initiated',
                'idempotency_key' => $idempotencyKey,
            ]);

            try {
                foreach ($order->items as $item) {
                    $response = $supplier->fulfill(
                        $order,
                        $item,
                        $idempotencyKey . '-' . $item->id
                    );

                    $attempt->update([
                        'response_payload' => $response,
                    ]);
                }

                $attempt->update([
                    'status' => 'fulfilled',
                    'fulfilled_at' => now(),
                ]);

                $order->update([
                    'status' => 'completed',
                    'fulfillment_status' => 'fulfilled',
                    'supplier_reference' =>
                        $attempt->supplier_reference,
                ]);

                $this->auditLogService->log(
                    'fulfillment.completed',
                    $order,
                    [
                        'fulfillment_attempt_id' => $attempt->id,
                        'supplier' => $supplierName,
                    ]
                );

                return $order->fresh();
            } catch (Throwable $exception) {
                $attempt->update([
                    'status' => 'failed',
                    'failed_at' => now(),
                    'response_payload' => [
                        'error' => $exception->getMessage(),
                    ],
                ]);

                $order->update([
                    'fulfillment_status' => 'failed',
                ]);

                $this->auditLogService->log(
                    'fulfillment.failed',
                    $order,
                    [
                        'fulfillment_attempt_id' => $attempt->id,
                        'supplier' => $supplierName,
                        'error' => $exception->getMessage(),
                    ]
                );

                throw $exception;
            }
        });
    }
}