<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PaymentAttempt;
use App\Services\SecurityReviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_paid_order_can_enter_security_review(): void
    {
        $order =
            $this->makeOrder('unpaid');

        $this->expectException(
            \RuntimeException::class
        );

        app(
            SecurityReviewService::class
        )->startReview($order);
    }

    public function test_approved_review_moves_order_to_processing(): void
    {
        $order =
            $this->makeOrder('paid');

        $service =
            app(
                SecurityReviewService::class
            );

        $review =
            $service->startReview(
                $order
            );

        $service->approve(
            $review,
            'Approved in automated test.'
        );

        $this->assertSame(
            'approved',
            $review->fresh()->status
        );

        $this->assertSame(
            'processing',
            $order->fresh()
                ->fulfillment_status
        );
    }

    public function test_rejected_paid_order_creates_refund_requirement(): void
    {
        $order =
            $this->makeOrder('paid');

        $attempt =
            PaymentAttempt::create([
                'order_id' =>
                    $order->id,

                'provider' =>
                    'test-provider',

                'status' =>
                    'paid',

                'amount' =>
                    $order->total,

                'currency' =>
                    $order->currency,

                'provider_payment_id' =>
                    'PAID-TEST-001',

                'idempotency_key' =>
                    'PAY-TEST-REFUND-001',

                'merchant_reference' =>
                    'MR-TEST-REFUND-001',

                'paid_at' =>
                    now(),
            ]);

        $service =
            app(
                SecurityReviewService::class
            );

        $review =
            $service->startReview(
                $order
            );

        $service->reject(
            $review,
            'Risk review rejected.'
        );

        $order->refresh();

        $this->assertSame(
            'rejected',
            $review->fresh()->status
        );

        $this->assertSame(
            'cancelled',
            $order->status
        );

        $this->assertSame(
            'refund_pending',
            $order->payment_status
        );

        $this->assertSame(
            'blocked',
            $order->fulfillment_status
        );

        $this->assertDatabaseHas(
            'refunds',
            [
                'order_id' =>
                    $order->id,

                'payment_attempt_id' =>
                    $attempt->id,

                'status' =>
                    'required',

                'currency' =>
                    'AZN',
            ]
        );
    }

    public function test_rejecting_same_review_twice_does_not_duplicate_refund(): void
    {
        $order =
            $this->makeOrder('paid');

        PaymentAttempt::create([
            'order_id' =>
                $order->id,

            'provider' =>
                'test-provider',

            'status' =>
                'paid',

            'amount' =>
                $order->total,

            'currency' =>
                $order->currency,

            'provider_payment_id' =>
                'PAID-TEST-002',

            'idempotency_key' =>
                'PAY-TEST-REFUND-002',

            'merchant_reference' =>
                'MR-TEST-REFUND-002',

            'paid_at' =>
                now(),
        ]);

        $service =
            app(
                SecurityReviewService::class
            );

        $review =
            $service->startReview(
                $order
            );

        $service->reject(
            $review,
            'Rejected.'
        );

        $service->reject(
            $review->fresh(),
            'Rejected again.'
        );

        $this->assertDatabaseCount(
            'refunds',
            1
        );
    }

    private function makeOrder(
        string $paymentStatus
    ): Order {
        return Order::create([
            'order_number' =>
                'ORD-TEST-' .
                strtoupper(
                    fake()
                        ->unique()
                        ->bothify(
                            '????####'
                        )
                ),

            'status' =>
                $paymentStatus === 'paid'
                    ? 'processing'
                    : 'pending',

            'payment_status' =>
                $paymentStatus,

            'fulfillment_status' =>
                'pending',

            'subtotal' => 20,
            'total' => 20,
            'currency' => 'AZN',

            'customer_email' =>
                fake()
                    ->unique()
                    ->safeEmail(),
        ]);
    }
}   