<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PaymentAttempt;
use App\Models\Refund;
use App\Models\SecurityReview;
use App\Models\WebhookEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentWebhookTest extends TestCase
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

    public function test_paid_webhook_marks_payment_paid_and_starts_review(): void
    {
        [$order, $attempt] =
            $this->makeUnpaidOrder();

        $this->sendWebhook(
            $attempt,
            'PAYMENT-001',
            'paid'
        )->assertOk();

        $order->refresh();
        $attempt->refresh();

        $this->assertSame(
            'paid',
            $attempt->status
        );

        $this->assertSame(
            'paid',
            $order->payment_status
        );

        $this->assertSame(
            'security_review',
            $order->fulfillment_status
        );

        $this->assertDatabaseHas(
            'security_reviews',
            [
                'order_id' =>
                    $order->id,

                'status' =>
                    'pending',
            ]
        );
    }

    public function test_duplicate_webhook_is_idempotent(): void
    {
        [$order, $attempt] =
            $this->makeUnpaidOrder();

        $this->sendWebhook(
            $attempt,
            'PAYMENT-002',
            'paid'
        )->assertOk();

        $this->sendWebhook(
            $attempt,
            'PAYMENT-002',
            'paid'
        )->assertOk();

        $this->assertSame(
            1,
            SecurityReview::query()
                ->where(
                    'order_id',
                    $order->id
                )
                ->count()
        );
    }

    public function test_paid_payment_cannot_be_downgraded(): void
    {
        [$order, $attempt] =
            $this->makeUnpaidOrder();

        $this->sendWebhook(
            $attempt,
            'PAYMENT-003',
            'paid'
        )->assertOk();

        $this->sendWebhook(
            $attempt->fresh(),
            'PAYMENT-003',
            'failed'
        )->assertOk();

        $this->assertSame(
            'paid',
            $attempt->fresh()->status
        );

        $this->assertSame(
            'paid',
            $order->fresh()->payment_status
        );
    }

    public function test_wrong_secret_is_rejected(): void
    {
        [, $attempt] =
            $this->makeUnpaidOrder();

        $this
            ->withHeader(
                'X-Webhook-Secret',
                'wrong-secret'
            )
            ->postJson(
                '/webhooks/payment',
                $this->payload(
                    $attempt,
                    'PAYMENT-004',
                    'paid'
                )
            )
            ->assertStatus(401);
    }

    public function test_amount_mismatch_is_rejected_without_changing_order(): void
    {
        [$order, $attempt] =
            $this->makeUnpaidOrder();

        $payload =
            $this->payload(
                $attempt,
                'PAYMENT-005',
                'paid'
            );

        $payload['amount'] = '1.00';

        $this
            ->withHeader(
                'X-Webhook-Secret',
                'test-webhook-secret'
            )
            ->postJson(
                '/webhooks/payment',
                $payload
            )
            ->assertStatus(422);

        $this->assertSame(
            'unpaid',
            $order->fresh()->payment_status
        );

        $this->assertSame(
            'initiated',
            $attempt->fresh()->status
        );
    }

    public function test_currency_mismatch_is_rejected(): void
    {
        [$order, $attempt] =
            $this->makeUnpaidOrder();

        $payload =
            $this->payload(
                $attempt,
                'PAYMENT-006',
                'paid'
            );

        $payload['currency'] = 'USD';

        $this
            ->withHeader(
                'X-Webhook-Secret',
                'test-webhook-secret'
            )
            ->postJson(
                '/webhooks/payment',
                $payload
            )
            ->assertStatus(422);

        $this->assertSame(
            'unpaid',
            $order->fresh()->payment_status
        );
    }

    public function test_unknown_merchant_reference_is_rejected(): void
    {
        [, $attempt] =
            $this->makeUnpaidOrder();

        $payload =
            $this->payload(
                $attempt,
                'PAYMENT-007',
                'paid'
            );

        $payload['merchant_reference'] =
            'MR-DOES-NOT-EXIST';

        $this
            ->withHeader(
                'X-Webhook-Secret',
                'test-webhook-secret'
            )
            ->postJson(
                '/webhooks/payment',
                $payload
            )
            ->assertStatus(422);
    }

    public function test_late_paid_webhook_for_cancelled_order_creates_refund_without_resurrecting_order(): void
    {
        [$order, $attempt] =
            $this->makeUnpaidOrder();

        $order->update([
            'status' => 'cancelled',
            'fulfillment_status' =>
                'blocked',
        ]);

        $this->sendWebhook(
            $attempt,
            'PAYMENT-008',
            'paid'
        )->assertOk();

        $order->refresh();

        $this->assertSame(
            'cancelled',
            $order->status
        );

        $this->assertSame(
            'refund_pending',
            $order->payment_status
        );

        $this->assertDatabaseHas(
            'refunds',
            [
                'payment_attempt_id' =>
                    $attempt->id,

                'status' =>
                    'required',
            ]
        );
    }

    public function test_second_paid_attempt_creates_separate_refund_without_cancelling_valid_order(): void
    {
        [$order, $attemptOne] =
            $this->makeUnpaidOrder();

        $this->sendWebhook(
            $attemptOne,
            'PAYMENT-009-A',
            'paid'
        )->assertOk();

        /*
         * Simulate a second provider checkout session
         * created before the first callback arrived.
         */
        $attemptTwo =
            PaymentAttempt::create([
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
                    'PAY-TEST-SECOND',

                'merchant_reference' =>
                    'MR-TEST-SECOND',
            ]);

        $this->sendWebhook(
            $attemptTwo,
            'PAYMENT-009-B',
            'paid'
        )->assertOk();

        $order->refresh();

        $this->assertSame(
            'processing',
            $order->status
        );

        $this->assertSame(
            'paid',
            $order->payment_status
        );

        $this->assertDatabaseHas(
            'refunds',
            [
                'payment_attempt_id' =>
                    $attemptTwo->id,

                'status' =>
                    'required',
            ]
        );
    }

    public function test_webhook_event_is_recorded(): void
    {
        [, $attempt] =
            $this->makeUnpaidOrder();

        $this->sendWebhook(
            $attempt,
            'PAYMENT-010',
            'paid'
        )->assertOk();

        $this->assertSame(
            1,
            WebhookEvent::query()->count()
        );

        $this->assertSame(
            'processed',
            WebhookEvent::query()
                ->first()
                ->processing_status
        );
    }

    private function sendWebhook(
        PaymentAttempt $attempt,
        string $providerPaymentId,
        string $status
    ) {
        return $this
            ->withHeader(
                'X-Webhook-Secret',
                'test-webhook-secret'
            )
            ->postJson(
                '/webhooks/payment',
                $this->payload(
                    $attempt,
                    $providerPaymentId,
                    $status
                )
            );
    }

    private function payload(
        PaymentAttempt $attempt,
        string $providerPaymentId,
        string $status
    ): array {
        return [
            'provider' =>
                $attempt->provider,

            'merchant_reference' =>
                $attempt->merchant_reference,

            'provider_payment_id' =>
                $providerPaymentId,

            'amount' =>
                (string) $attempt->amount,

            'currency' =>
                $attempt->currency,

            'status' =>
                $status,
        ];
    }

    private function makeUnpaidOrder(): array
    {
        $order = Order::create([
            'order_number' =>
                'ORD-TEST-' .
                strtoupper(
                    fake()
                        ->unique()
                        ->bothify(
                            '????####'
                        )
                ),

            'status' => 'pending',
            'payment_status' => 'unpaid',
            'fulfillment_status' => 'pending',

            'subtotal' => 10,
            'total' => 10,
            'currency' => 'AZN',

            'customer_email' =>
                fake()
                    ->unique()
                    ->safeEmail(),
        ]);

        $attempt =
            PaymentAttempt::create([
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
                    'PAY-TEST-' .
                    strtoupper(
                        fake()
                            ->unique()
                            ->bothify(
                                '????####'
                            )
                    ),

                'merchant_reference' =>
                    'MR-TEST-' .
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