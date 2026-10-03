<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderItemMoneyTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_item_total_uses_exact_minor_units_for_0_29_times_3(): void
    {
        $order = $this->createOrder(
            '0.29',
            3
        );

        $item = $order->items->first();

        $this->assertNotNull($item);

        $this->assertSame(
            '0.87',
            $item->total_price
        );

        $this->assertSame(
            '0.87',
            $order->fresh()->subtotal
        );

        $this->assertSame(
            '0.87',
            $order->fresh()->total
        );
    }

    public function test_order_item_total_uses_exact_minor_units_for_19_99_times_3(): void
    {
        $order = $this->createOrder(
            '19.99',
            3
        );

        $item = $order->items->first();

        $this->assertNotNull($item);

        $this->assertSame(
            '59.97',
            $item->total_price
        );

        $this->assertSame(
            '59.97',
            $order->fresh()->subtotal
        );

        $this->assertSame(
            '59.97',
            $order->fresh()->total
        );
    }

    private function createOrder(
        string $price,
        int $quantity
    ) {
        $product = Product::create([
            'name' =>
                'Money Test Product ' . Str::uuid(),
            'slug' =>
                'money-test-' . Str::uuid(),
            'category' =>
                'Direct Top-Up',
            'supplier' =>
                'test-supplier',
            'supplier_product_code' =>
                'MONEY-' . Str::uuid(),
            'price' =>
                $price,
            'currency' =>
                'AZN',
            'catalog_visible' =>
                true,
            'is_active' =>
                true,
            'resale_verified' =>
                true,
            'bank_approved' =>
                true,
            'description' =>
                'Exact money regression test product.',
        ]);

        return app(OrderService::class)
            ->createOrder([
                'product_id' =>
                    $product->id,
                'quantity' =>
                    $quantity,
                'customer_email' =>
                    'money-test-' . Str::uuid() . '@example.com',
                'customer_name' =>
                    'Money Test',
                'idempotency_key' =>
                    (string) Str::uuid(),
            ]);
    }
}
