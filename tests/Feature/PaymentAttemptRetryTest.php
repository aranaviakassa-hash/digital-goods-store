<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class PaymentAttemptRetryTest extends TestCase
{
    use RefreshDatabase;

    private function createSellableProduct(): Product
    {
        return Product::create([
            'name' => 'Payment Retry Test Product',
            'slug' => 'payment-retry-test-product-' . uniqid(),
            'category' => 'Direct Top-Up',
            'supplier' => 'test-supplier',
            'supplier_product_code' => 'TEST-PAY-001',
            'price' => '10.00',
            'currency' => 'AZN',

            'catalog_visible' => true,
            'is_active' => true,
            'resale_verified' => true,
            'bank_approved' => true,

            'description' => 'Payment retry test product.',
        ]);
    }

    private function createOrder(
        OrderService $orderService
    ): Order {
        $product = $this->createSellableProduct();

        return $orderService->createOrder([
            'product_id' => $product->id,
            'quantity' => 1,
            'customer_email' => 'payment-test@example.com',
            'customer_name' => 'Payment Test',
            'idempotency_key' => (string) \Illuminate\Support\Str::uuid(),
        ]);
    }

    public function test_same_key_same_order_same_provider_returns_same_attempt(): void
    {
        $service = app(OrderService::class);
        $order = $this->createOrder($service);

        $first = $service->createPaymentAttempt(
            $order,
            'abb',
            'PAY-TEST-SAME'
        );

        $second = $service->createPaymentAttempt(
            $order,
            'abb',
            'PAY-TEST-SAME'
        );

        $this->assertSame(
            $first->id,
            $second->id
        );

        $this->assertDatabaseCount(
            'payment_attempts',
            1
        );
    }

    public function test_same_key_cannot_be_reused_for_another_order(): void
    {
        $service = app(OrderService::class);

        $firstOrder = $this->createOrder($service);
        $secondOrder = $this->createOrder($service);

        $service->createPaymentAttempt(
            $firstOrder,
            'abb',
            'PAY-TEST-CROSS-ORDER'
        );

        $this->expectException(
            RuntimeException::class
        );

        $this->expectExceptionMessage(
            'Payment idempotency key is already in use for another payment context.'
        );

        $service->createPaymentAttempt(
            $secondOrder,
            'abb',
            'PAY-TEST-CROSS-ORDER'
        );
    }

    public function test_same_key_cannot_be_reused_for_another_provider(): void
    {
        $service = app(OrderService::class);
        $order = $this->createOrder($service);

        $service->createPaymentAttempt(
            $order,
            'abb',
            'PAY-TEST-CROSS-PROVIDER'
        );

        $this->expectException(
            RuntimeException::class
        );

        $this->expectExceptionMessage(
            'Payment idempotency key is already in use for another payment context.'
        );

        $service->createPaymentAttempt(
            $order,
            'other-provider',
            'PAY-TEST-CROSS-PROVIDER'
        );
    }

    public function test_failed_attempt_can_be_retried_with_new_key(): void
    {
        $service = app(OrderService::class);
        $order = $this->createOrder($service);

        $firstAttempt =
            $service->createPaymentAttempt(
                $order,
                'abb',
                'PAY-TEST-FAILED-1'
            );

        $service->markPaymentFailed(
            $firstAttempt,
            [
                'reason' => 'declined',
            ]
        );

        $order->refresh();

        $this->assertSame(
            'failed',
            $order->payment_status
        );

        $secondAttempt =
            $service->createPaymentAttempt(
                $order,
                'abb',
                'PAY-TEST-FAILED-2'
            );

        $this->assertNotSame(
            $firstAttempt->id,
            $secondAttempt->id
        );

        $this->assertSame(
            'initiated',
            $secondAttempt->status
        );

        $order->refresh();

        $this->assertSame(
            'unpaid',
            $order->payment_status
        );

        $this->assertDatabaseCount(
            'payment_attempts',
            2
        );
    }

    public function test_paid_order_cannot_start_another_payment_attempt(): void
    {
        $service = app(OrderService::class);
        $order = $this->createOrder($service);

        $attempt =
            $service->createPaymentAttempt(
                $order,
                'abb',
                'PAY-TEST-PAID-1'
            );

        $service->markPaymentPaid(
            $attempt,
            'ABB-PAYMENT-001'
        );

        /*
         * In the current architecture the webhook/payment flow
         * is responsible for updating the order payment state.
         * Simulate that final paid state here.
         */
        $order->update([
            'payment_status' => 'paid',
        ]);

        $this->expectException(
            RuntimeException::class
        );

        $this->expectExceptionMessage(
            'Order is not eligible for a new payment attempt.'
        );

        $service->createPaymentAttempt(
            $order->fresh(),
            'abb',
            'PAY-TEST-PAID-2'
        );
    }
}