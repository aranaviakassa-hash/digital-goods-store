<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderEvidence;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CheckoutEvidenceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['company.live_payment_enabled' => true]);
    }

    public function test_checkout_records_policy_acceptance_evidence_with_current_versions(): void
    {
        $product = Product::create([
            'name' => 'Checkout Evidence Product',
            'slug' => 'checkout-evidence-product',
            'category' => 'Direct Top-Up',
            'supplier' => 'test-supplier',
            'supplier_product_code' => 'TEST-EVIDENCE-001',
            'price' => '10.00',
            'currency' => 'AZN',
            'catalog_visible' => true,
            'is_active' => true,
            'resale_verified' => true,
            'bank_approved' => true,
            'description' => 'Checkout evidence test product.',
        ]);

        $response = $this
            ->withHeaders([
                'User-Agent' => 'NEXORA Checkout Evidence Test',
            ])
            ->post(
                route('checkout.store', $product),
                [
                    'customer_email' => 'Customer@Example.com',
                    'customer_name' => 'Test Customer',
                    'quantity' => 1,
                    'account_identifier' => 'PLAYER-12345',
                    'secondary_identifier' => 'ZONE-001',
                    'server_region' => 'EU',
                    'idempotency_key' => (string) Str::uuid(),
                    'terms' => '1',
                    'refund_policy' => '1',
                    'delivery_policy' => '1',
                    'customer_data_confirmed' => '1',
                ]
            );

        $order = Order::query()
            ->where('customer_email', 'customer@example.com')
            ->first();

        $this->assertNotNull($order, 'Checkout should create an order.');

        $response->assertRedirect(route('orders.show', $order));

        $evidence = OrderEvidence::query()
            ->where('order_id', $order->id)
            ->first();

        $this->assertNotNull($evidence, 'Checkout should create order evidence.');
        $this->assertSame('2026-10-03-v1', $evidence->terms_version);
        $this->assertSame('2026-10-03-v1', $evidence->refund_policy_version);
        $this->assertSame('2026-10-03-v1', $evidence->delivery_policy_version);
        $this->assertSame('2026-10-03-v1', $evidence->privacy_policy_version);
        $this->assertNotNull($evidence->terms_accepted_at);
        $this->assertNotNull($evidence->refund_policy_accepted_at);
        $this->assertNotNull($evidence->delivery_policy_accepted_at);
        $this->assertNotNull($evidence->customer_data_confirmed_at);
        $this->assertSame('NEXORA Checkout Evidence Test', $evidence->user_agent);

        $order->load('items');
        $orderItem = $order->items->first();

        $this->assertNotNull($orderItem);
        $this->assertSame('PLAYER-12345', $orderItem->delivery_data['account_identifier']);
        $this->assertSame('ZONE-001', $orderItem->delivery_data['secondary_identifier']);
        $this->assertSame('EU', $orderItem->delivery_data['server_region']);
        $this->assertTrue($orderItem->delivery_data['customer_confirmed']);
    }

    public function test_checkout_rejects_order_when_required_acceptances_are_missing(): void
    {
        $product = Product::create([
            'name' => 'Rejected Evidence Product',
            'slug' => 'rejected-evidence-product',
            'category' => 'Direct Top-Up',
            'supplier' => 'test-supplier',
            'supplier_product_code' => 'TEST-EVIDENCE-002',
            'price' => '10.00',
            'currency' => 'AZN',
            'catalog_visible' => true,
            'is_active' => true,
            'resale_verified' => true,
            'bank_approved' => true,
            'description' => 'Rejected checkout evidence test product.',
        ]);

        $response = $this
            ->from(route('checkout.show', $product))
            ->post(
                route('checkout.store', $product),
                [
                    'customer_email' => 'blocked@example.com',
                    'customer_name' => 'Blocked Customer',
                    'quantity' => 1,
                    'account_identifier' => 'PLAYER-999',
                    'secondary_identifier' => null,
                    'server_region' => null,
                    'idempotency_key' => (string) Str::uuid(),
                ]
            );

        $response
            ->assertRedirect(route('checkout.show', $product))
            ->assertSessionHasErrors([
                'terms',
                'refund_policy',
                'delivery_policy',
                'customer_data_confirmed',
            ]);

        $this->assertDatabaseMissing('orders', [
            'customer_email' => 'blocked@example.com',
        ]);

        $this->assertSame(0, OrderEvidence::query()->count());
    }
}
