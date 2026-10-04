<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PaymentAttempt;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class ExternalAuditSecurityRegressionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['company.live_payment_enabled' => true]);
    }

    public function test_admin_account_cannot_sign_in_through_storefront_login(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('StrongPass123!'),
        ]);

        $admin->forceFill([
            'is_admin' => true,
            'email_verified_at' => now(),
        ])->save();

        $this->post(route('login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'StrongPass123!',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_checkout_replay_cannot_change_delivery_data_or_evidence(): void
    {
        $product = $this->sellableProduct();
        $key = (string) Str::uuid();

        $payload = [
            'customer_email' => 'buyer@example.com',
            'customer_name' => 'Buyer',
            'quantity' => 1,
            'account_identifier' => 'PLAYER-ORIGINAL',
            'secondary_identifier' => 'ZONE-1',
            'server_region' => 'EU',
            'idempotency_key' => $key,
            'terms' => '1',
            'refund_policy' => '1',
            'delivery_policy' => '1',
            'customer_data_confirmed' => '1',
        ];

        $this->post(route('checkout.store', $product), $payload)
            ->assertRedirect();

        $order = Order::query()
            ->where('idempotency_key', $key)
            ->firstOrFail();

        $order->load(['items', 'evidence']);
        $originalAcceptedAt = $order->evidence->terms_accepted_at?->toISOString();

        $payload['account_identifier'] = 'PLAYER-ATTACKER';
        $payload['secondary_identifier'] = 'ZONE-999';

        $this->post(route('checkout.store', $product), $payload)
            ->assertRedirect();

        $order->refresh()->load(['items', 'evidence']);

        $this->assertSame(
            'PLAYER-ORIGINAL',
            $order->items->first()->delivery_data['account_identifier']
        );
        $this->assertSame(
            'ZONE-1',
            $order->items->first()->delivery_data['secondary_identifier']
        );
        $this->assertSame(
            $originalAcceptedAt,
            $order->evidence->terms_accepted_at?->toISOString()
        );
    }

    public function test_paid_webhook_replay_with_different_body_shape_does_not_regress_order(): void
    {
        config(['payments.webhook_secret' => 'test-webhook-secret']);

        $order = Order::create([
            'order_number' => 'ORD-REPLAY-001',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'fulfillment_status' => 'pending',
            'subtotal' => '10.00',
            'total' => '10.00',
            'currency' => 'AZN',
            'customer_email' => 'replay@example.com',
        ]);

        PaymentAttempt::create([
            'order_id' => $order->id,
            'provider' => 'test-provider',
            'status' => 'initiated',
            'amount' => '10.00',
            'currency' => 'AZN',
            'idempotency_key' => 'PAY-REPLAY-001',
            'merchant_reference' => 'MR-REPLAY-001',
        ]);

        $payload = [
            'provider' => 'test-provider',
            'merchant_reference' => 'MR-REPLAY-001',
            'provider_payment_id' => 'PROVIDER-REPLAY-001',
            'amount' => '10.00',
            'currency' => 'AZN',
            'status' => 'paid',
        ];

        $this->withHeader('X-Webhook-Secret', 'test-webhook-secret')
            ->postJson('/webhooks/payment', $payload)
            ->assertOk();

        $order->forceFill([
            'status' => 'completed',
            'fulfillment_status' => 'fulfilled',
        ])->save();

        $payload['amount'] = '10.0';

        $this->withHeader('X-Webhook-Secret', 'test-webhook-secret')
            ->postJson('/webhooks/payment', $payload)
            ->assertOk();

        $order->refresh();

        $this->assertSame('completed', $order->status);
        $this->assertSame('fulfilled', $order->fulfillment_status);
        $this->assertSame('paid', $order->payment_status);
    }

    public function test_failed_attempt_does_not_overwrite_refund_pending_order_state(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-REFUND-001',
            'status' => 'cancelled',
            'payment_status' => 'refund_pending',
            'fulfillment_status' => 'pending',
            'subtotal' => '10.00',
            'total' => '10.00',
            'currency' => 'AZN',
            'customer_email' => 'refund@example.com',
        ]);

        $attempt = PaymentAttempt::create([
            'order_id' => $order->id,
            'provider' => 'test-provider',
            'status' => 'initiated',
            'amount' => '10.00',
            'currency' => 'AZN',
            'idempotency_key' => 'PAY-REFUND-001',
            'merchant_reference' => 'MR-REFUND-001',
        ]);

        app(OrderService::class)->markPaymentFailed($attempt, [
            'source' => 'regression_test',
        ]);

        $this->assertSame('failed', $attempt->fresh()->status);
        $this->assertSame('refund_pending', $order->fresh()->payment_status);
    }

    private function sellableProduct(): Product
    {
        return Product::create([
            'name' => 'Audit Regression Product',
            'slug' => 'audit-regression-product',
            'category' => 'Direct Top-Up',
            'supplier' => 'test-supplier',
            'supplier_product_code' => 'AUDIT-001',
            'price' => '10.00',
            'currency' => 'AZN',
            'catalog_visible' => true,
            'is_active' => true,
            'resale_verified' => true,
            'bank_approved' => true,
            'description' => 'Audit regression test product.',
        ]);
    }
}
