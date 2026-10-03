<?php

namespace App\Contracts;

use App\Models\Order;
use App\Models\OrderItem;

interface SupplierAdapter
{
    /**
     * Execute one irreversible supplier fulfillment action.
     *
     * Adapter implementations must normalize successful responses to:
     *
     * [
     *     'supplier_reference' => ?string,
     *     'response' => array,
     * ]
     *
     * The same idempotency key must always represent the same
     * order item fulfillment request.
     */
    public function fulfill(
        Order $order,
        OrderItem $item,
        string $idempotencyKey
    ): array;
}