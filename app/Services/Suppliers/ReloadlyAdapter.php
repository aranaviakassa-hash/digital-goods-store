<?php

namespace App\Services\Suppliers;

use App\Contracts\SupplierAdapter;
use App\Models\Order;
use App\Models\OrderItem;
use RuntimeException;

class ReloadlyAdapter implements SupplierAdapter
{
    public function fulfill(
        Order $order,
        OrderItem $item,
        string $idempotencyKey
    ): array {
        if (! config('reloadly.enabled')) {
            throw new RuntimeException(
                'Reloadly live fulfillment is disabled.'
            );
        }

        if (
            blank(config('reloadly.client_id'))
            || blank(config('reloadly.client_secret'))
            || blank(config('reloadly.base_url'))
        ) {
            throw new RuntimeException(
                'Reloadly credentials are not configured.'
            );
        }

        /*
         * Real Reloadly HTTP request burada olacaq.
         *
         * Provider auth + product-specific payload-u
         * Reloadly-nin real API kontraktına əsasən qoşacağıq.
         */

        throw new RuntimeException(
            'Reloadly API request is not implemented yet.'
        );
    }
}