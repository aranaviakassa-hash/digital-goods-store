<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentAttempt;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class OrderService
{
    public function createOrder(array $data): Order
    {
        try {
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
                    'order_number' => $this->generateOrderNumber(),
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
        } catch (QueryException $exception) {
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

            throw $exception;
        }
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
            $lockedAttempt = PaymentAttempt::query()
                ->whereKey($attempt->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedAttempt->status === 'paid') {
                return $lockedAttempt;
            }

            $order = Order::query()
                ->whereKey($lockedAttempt->order_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->payment_status === 'paid') {
                return $lockedAttempt;
            }

            $lockedAttempt->update([
                'status' => 'paid',
                'provider_payment_id' => $providerPaymentId
                    ?? $lockedAttempt->provider_payment_id,
                'response_payload' => $responsePayload,
                'paid_at' => now(),
                'failed_at' => null,
            ]);

            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'fulfillment_status' => 'security_review',
                'payment_reference' => $providerPaymentId
                    ?? $lockedAttempt->provider_payment_id,
            ]);

            return $lockedAttempt->fresh();
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
            $lockedAttempt = PaymentAttempt::query()
                ->whereKey($attempt->id)
                ->lockForUpdate()
                ->firstOrFail();

            $order = Order::query()
                ->whereKey($lockedAttempt->order_id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $lockedAttempt->status === 'paid'
                || $order->payment_status === 'paid'
            ) {
                return $lockedAttempt;
            }

            if ($lockedAttempt->status === 'failed') {
                return $lockedAttempt;
            }

            $lockedAttempt->update([
                'status' => 'failed',
                'response_payload' => $responsePayload,
                'failed_at' => now(),
            ]);

            $order->update([
                'payment_status' => 'failed',
            ]);

            return $lockedAttempt->fresh();
        });
    }

    private function generateOrderNumber(): string
    {
        do {
            $orderNumber =
                'ORD-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(Str::random(8));
        } while (
            Order::query()
                ->where('order_number', $orderNumber)
                ->exists()
        );

        return $orderNumber;
    }
}