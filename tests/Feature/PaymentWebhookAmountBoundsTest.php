<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PaymentAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentWebhookAmountBoundsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'payments.webhook_secret' =>
                'test-webhook-secret',
        ]);
    }

    public function test_amount_exceeding_database_precision_is_rejected_by_validation(): void
    {
        [$order, $attempt] =
            $this->makeUnpaidOrder();

        $this
            ->withHeader(
                'X-Webhook-Secret',
                'test-webhook-secret'
            )
            ->postJson(
                '/webhooks/payment',
                [
                    'provider' =>
                        $attempt->provider,

                    'merchant_reference' =>
                        $attempt->merchant_reference,

                    'provider_payment_id' =>
                        'PAYMENT-OVERFLOW-001',

                    'amount' =>
                        '999999999999999999999999.99',

                    'currency' =>
                        'AZN',

                    'status' =>
                        'paid',
                ]
            )
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'amount',
            ]);

        $this->assertSame(
            'initiated',
            $attempt->fresh()->status
        );

        $this->assertSame(
            'unpaid',
            $order->fresh()->payment_status
        );
    }

    public function test_maximum_decimal_10_2_amount_shape_passes_amount_validation(): void
    {
        $order = Order::create([
            'order_number' =>
                'ORD-BOUND-MAX',

            'status' =>
                'pending',

            'payment_status' =>
                'unpaid',

            'fulfillment_status' =>
                'pending',

            'subtotal' =>
                '99999999.99',

            'total' =>
                '99999999.99',

            'currency' =>
                'AZN',

            'customer_email' =>
                'bounds@example.test',
        ]);

        $attempt = PaymentAttempt::create([
            'order_id' =>
                $order->id,

            'provider' =>
                'test-provider',

            'status' =>
                'initiated',

            'amount' =>
                '99999999.99',

            'currency' =>
                'AZN',

            'idempotency_key' =>
                'PAY-BOUND-MAX',

            'merchant_reference' =>
                'MR-BOUND-MAX',
        ]);

        $this
            ->withHeader(
                'X-Webhook-Secret',
                'test-webhook-secret'
            )
            ->postJson(
                '/webhooks/payment',
                [
                    'provider' =>
                        $attempt->provider,

                    'merchant_reference' =>
                        $attempt->merchant_reference,

                    'provider_payment_id' =>
                        'PAYMENT-BOUND-MAX',

                    'amount' =>
                        '99999999.99',

                    'currency' =>
                        'AZN',

                    'status' =>
                        'paid',
                ]
            )
            ->assertOk();

        $this->assertSame(
            'paid',
            $attempt->fresh()->status
        );
    }

    private function makeUnpaidOrder(): array
    {
        $order = Order::create([
            'order_number' =>
                'ORD-BOUND-' .
                strtoupper(
                    fake()
                        ->unique()
                        ->bothify(
                            '????####'
                        )
                ),

            'status' =>
                'pending',

            'payment_status' =>
                'unpaid',

            'fulfillment_status' =>
                'pending',

            'subtotal' =>
                '10.00',

            'total' =>
                '10.00',

            'currency' =>
                'AZN',

            'customer_email' =>
                fake()
                    ->unique()
                    ->safeEmail(),
        ]);

        $attempt = PaymentAttempt::create([
            'order_id' =>
                $order->id,

            'provider' =>
                'test-provider',

            'status' =>
                'initiated',

            'amount' =>
                '10.00',

            'currency' =>
                'AZN',

            'idempotency_key' =>
                'PAY-BOUND-' .
                strtoupper(
                    fake()
                        ->unique()
                        ->bothify(
                            '????####'
                        )
                ),

            'merchant_reference' =>
                'MR-BOUND-' .
                strtoupper(
                    fake()
                        ->unique()
                        ->bothify(
                            '????####'
                        )
                ),
        ]);

        return [
            $order,
            $attempt,
        ];
    }
}
