<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class FulfillmentService
{
    public function start(Order $order): Order
    {
        if ($order->payment_status !== 'paid') {
            throw new RuntimeException(
                'Order must be paid before fulfillment.'
            );
        }

        if ($order->fulfillment_status !== 'processing') {
            throw new RuntimeException(
                'Order must pass security review first.'
            );
        }

        return $order;
    }

    public function markFulfilled(
        Order $order,
        ?string $supplierReference = null
    ): Order {
        return DB::transaction(function () use (
            $order,
            $supplierReference
        ) {
            $order->update([
                'status' => 'completed',
                'fulfillment_status' => 'fulfilled',
                'supplier_reference' => $supplierReference,
            ]);

            return $order->fresh();
        });
    }

    public function markFailed(Order $order): Order
    {
        $order->update([
            'fulfillment_status' => 'failed',
        ]);

        return $order->fresh();
    }
}