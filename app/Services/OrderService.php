<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentAttempt;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class OrderService
{
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $idempotencyKey = $data['idempotency_key'] ?? null;

            if ($idempotencyKey) {
                $existingOrder = Order::query()
                    ->where('idempotency_key', $idempotencyKey)
                    ->first();

                if ($existingOrder) {
                    return $existingOrder->load([
                        'items',
                        'paymentAttempts',
                    ]);
                }
            }

            $product = Product::query()
                ->whereKey($data['product_id'])
                ->where('is_active', true)
                ->where('resale_verified', true)
                ->where('bank_approved', true)
                ->first();

            if (! $product) {
                throw new RuntimeException(
                    'Product is not available for sale.'
                );
            }

            $quantity = max(
                (int) ($data['quantity'] ?? 1),
                1
            );

            $order = Order::create([
                'idempotency_key' => $idempotencyKey,
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

            return $order->fresh([
                'items',
                'paymentAttempts',
            ]);
        });
    }

    public function createPaymentAttempt(
        Order $order,
        string $provider,
        ?string $idempotencyKey = null,
        array $requestPayload = []
    ): PaymentAttempt {
        return DB::transaction(function () use (
            $order,
            $provider,
            $idempotencyKey,
            $requestPayload
        ) {
            $idempotencyKey ??=
                'PAY-' . strtoupper(Str::uuid()->toString());

            $existingAttempt = PaymentAttempt::query()
                ->where('idempotency_key', $idempotencyKey)
                ->first();

            if ($existingAttempt) {
                return $existingAttempt;
            }

            $attempt = PaymentAttempt::create([
                'order_id' => $order->id,
                'provider' => $provider,
                'status' => 'initiated',

                'amount' => $order->total,
                'currency' => $order->currency,

                'idempotency_key' => $idempotencyKey,
                'request_payload' => $requestPayload,
            ]);

            $order->update([
                'payment_provider' => $provider,
                'payment_status' => 'unpaid',
            ]);

            return $attempt;
        });
    }

    public function markPaymentPaid(
        PaymentAttempt $attempt,
        ?string $providerPaymentId = null,
        array $responsePayload = []
    ): PaymentAttempt {
        return DB::transaction(function () use (
            $attempt,
            $providerPaymentId,
            $responsePayload
        ) {
            if ($attempt->status === 'paid') {
                return $attempt;
            }

            $attempt->update([
                'status' => 'paid',
                'provider_payment_id' => $providerPaymentId,
                'response_payload' => $responsePayload,
                'paid_at' => now(),
                'failed_at' => null,
            ]);

            $attempt->order->update([
                'payment_status' => 'paid',
                'status' => 'processing',

                // Payment-dən sonra məhsulu dərhal vermirik.
                // Əvvəl fraud/security yoxlamasına gedir.
                'fulfillment_status' => 'security_review',

                'payment_reference' =>
                    $providerPaymentId,
            ]);

            return $attempt->fresh();
        });
    }

    public function markPaymentFailed(
        PaymentAttempt $attempt,
        array $responsePayload = []
    ): PaymentAttempt {
        return DB::transaction(function () use (
            $attempt,
            $responsePayload
        ) {
            $attempt->update([
                'status' => 'failed',
                'response_payload' => $responsePayload,
                'failed_at' => now(),
            ]);

            $attempt->order->update([
                'payment_status' => 'failed',
            ]);

            return $attempt->fresh();
        });
    }
}