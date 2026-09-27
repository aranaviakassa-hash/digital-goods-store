<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class OrderService
{
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $product = Product::query()
                ->whereKey($data['product_id'])
                ->where('is_active', true)
                ->where('resale_verified', true)
                ->where('bank_approved', true)
                ->first();

            if (! $product) {
                throw new RuntimeException('Product is not available for sale.');
            }

            $quantity = max((int) ($data['quantity'] ?? 1), 1);

            $order = Order::create([
                'user_id' => $data['user_id'] ?? null,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'fulfillment_status' => 'pending',
                'subtotal' => 0,
                'total' => 0,
                'currency' => $product->currency,
                'customer_email' => $data['customer_email'],
                'customer_name' => $data['customer_name'] ?? null,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_code' => $product->supplier_product_code,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'currency' => $product->currency,
            ]);

            return $order->fresh(['items']);
        });
    }
}