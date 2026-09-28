<?php

namespace App\Contracts;

use App\Models\Order;
use App\Models\OrderItem;

interface SupplierAdapter
{
    public function fulfill(
        Order $order,
        OrderItem $item,
        string $idempotencyKey
    ): array;
}