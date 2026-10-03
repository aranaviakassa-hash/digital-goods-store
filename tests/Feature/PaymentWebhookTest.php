<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PaymentAttempt;
use App\Models\SecurityReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'payments.webhook_secret' => 'test-webhook-secret',
        ]);
    }

    public function test_paid_webhook_marks_payment_paid_and_starts_review(): void
    {
        [$order, $attempt] = $this->makeUnpaidOrder();

        $response = $this
            ->withHeader(
                'X-Webhook-Secret',
                'test-webhook-secret'
            )
            ->postJson('/webhooks/payment', [
                'order_number' => $order->order_number,
                'provider' => 'test-provider',
                'provider_payment_id' => 'PAYMENT-001',
                'status' => 'paid',
            ]);

        $response->assertOk();

        $attempt->refresh();
        $order->refresh();

        $this->assertSame('paid', $attempt->status);
        $this->assertSame(
            'PAYMENT-001',
            $attempt->provider_payment_id
        );
        $this->assertSame('paid', $order->payment_status);
        $this->assertSame(
            'security_review',
            $order->fulfillment_status
        );

        $this->assertDatabaseHas(
            'security_reviews',
            [
                'order_id' => $order->id,
                'status' => 'pending',
            ]
        );
    }

    public function test_duplicate_paid_webhook_does_not_create_duplicate_review(): void
    {
        [$order] = $this->makeUnpaidOrder();

        $payload = [
            'order_number' => $order->order_number,
            'provider' => 'test-provider',
            'provider_payment_id' => 'PAYMENT-002',
            'status' => 'paid',
        ];

        $this
            ->withHeader(
                'X-Webhook-Secret',
                'test-webhook-secret'
            )
            ->postJson('/webhooks/payment', $payload)
            ->assertOk();

        $this
            ->withHeader(
                'X-Webhook-Secret',
                'test-webhook-secret'
            )
            ->postJson('/webhooks/payment', $payload)
            ->assertOk();

        $this->assertSame(
            1,
            SecurityReview::query()
                ->where('order_id', $order->id)
                ->count()
        );
    }

    public function test_paid_payment_cannot_be_downgraded_by_failed_webhook(): void
    {
        [$order, $attempt] = $this->makeUnpaidOrder();

        $attempt->update([
            'status' => 'paid',
            'provider_payment_id' => 'PAYMENT-003',
            'paid_at' => now(),
        ]);

        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'fulfillment_status' => 'security_review',
        ]);

        $this
            ->withHeader(
                'X-Webhook-Secret',
                'test-webhook-secret'
            )
            ->postJson('/webhooks/payment', [
                'order_number' => $order->order_number,
                'provider' => 'test-provider',
                'provider_payment_id' => 'PAYMENT-003',
                'status' => 'failed',
            ])
            ->assertOk();

        $this->assertSame(
            'paid',
            $order->fresh()->payment_status
        );

        $this->assertSame(
            'paid',
            $attempt->fresh()->status
        );
    }

    public function test_wrong_webhook_secret_is_rejected(): void
    {
        [$order] = $this->makeUnpaidOrder();

        $this
            ->withHeader(
                'X-Webhook-Secret',
                'wrong-secret'
            )
            ->postJson('/webhooks/payment', [
                'order_number' => $order->order_number,
                'provider' => 'test-provider',
                'provider_payment_id' => 'PAYMENT-004',
                'status' => 'paid',
            ])
            ->assertStatus(401);
    }

    private function makeUnpaidOrder(): array
    {
        $order = Order::create([
            'order_number' =>
                'ORD-TEST-' .
                strtoupper(
                    fake()->unique()->bothify('????####')
                ),
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'fulfillment_status' => 'pending',
            'subtotal' => 10,
            'total' => 10,
            'currency' => 'AZN',
            'customer_email' =>
                fake()->unique()->safeEmail(),
        ]);

        $attempt = PaymentAttempt::create([
            'order_id' => $order->id,
            'provider' => 'test-provider',
            'status' => 'initiated',
            'amount' => 10,
            'currency' => 'AZN',
            'idempotency_key' =>
                'PAY-TEST-' .
                strtoupper(
                    fake()->unique()->bothify('????####')
                ),
        ]);

        return [$order, $attempt];
    }
}