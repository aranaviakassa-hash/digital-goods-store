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
                $idempotencyKey =
                    $data['idempotency_key'] ?? null;

                if ($idempotencyKey) {
                    $existingOrder = Order::query()
                        ->where(
                            'idempotency_key',
                            $idempotencyKey
                        )
                        ->first();

                    if ($existingOrder) {
                        return $existingOrder->load([
                            'items',
                            'paymentAttempts',
                        ]);
                    }
                }

                /*
                 * Product availability must be enforced here as well,
                 * not only in the HTTP controller.
                 */
                $product = Product::query()
                    ->whereKey(
                        $data['product_id']
                    )
                    ->sellable()
                    ->first();

                if (! $product) {
                    throw new RuntimeException(
                        'Product is not available for sale.'
                    );
                }

                /*
                 * A sellable product must always have a real price.
                 * Product::sellable() already enforces this, but this
                 * defensive check prevents accidental zero/null orders
                 * if the model rules are changed later.
                 */
                if ($product->price === null) {
                    throw new RuntimeException(
                        'Product price is not available.'
                    );
                }

                $quantity = max(
                    (int) (
                        $data['quantity']
                        ?? 1
                    ),
                    1
                );

                $order = Order::create([
                    'order_number' =>
                        $this->generateOrderNumber(),

                    'idempotency_key' =>
                        $idempotencyKey,

                    'user_id' =>
                        $data['user_id'] ?? null,

                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'fulfillment_status' => 'pending',

                    'subtotal' => 0,
                    'total' => 0,

                    'currency' =>
                        strtoupper(
                            $product->currency
                        ),

                    'customer_email' =>
                        $data['customer_email'],

                    'customer_name' =>
                        $data['customer_name']
                        ?? null,
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,

                    'product_name' =>
                        $product->name,

                    'product_code' =>
                        $product
                            ->supplier_product_code,

                    'quantity' => $quantity,

                    'unit_price' =>
                        $product->price,

                    'currency' =>
                        strtoupper(
                            $product->currency
                        ),
                ]);

                return $order->fresh([
                    'items',
                    'paymentAttempts',
                ]);
            });
        } catch (QueryException $exception) {
            $idempotencyKey =
                $data['idempotency_key'] ?? null;

            if ($idempotencyKey) {
                $existingOrder = Order::query()
                    ->where(
                        'idempotency_key',
                        $idempotencyKey
                    )
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
        $provider = trim($provider);

        if ($provider === '') {
            throw new RuntimeException(
                'Payment provider is required.'
            );
        }

        $idempotencyKey ??=
            'PAY-' .
            strtoupper(
                Str::uuid()->toString()
            );

        /*
         * The database currently protects idempotency_key with a
         * global unique constraint. If two concurrent requests race,
         * the losing transaction may receive a unique violation.
         * Handle that safely outside the transaction.
         */
        try {
            return DB::transaction(function () use (
                $order,
                $provider,
                $idempotencyKey,
                $requestPayload
            ) {
                $lockedOrder = Order::query()
                    ->whereKey($order->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                 * Resolve an existing idempotent request BEFORE
                 * checking retry eligibility.
                 *
                 * This means replaying the same request after a
                 * failed attempt returns the same attempt instead
                 * of creating another one.
                 */
                $existingAttempt =
                    PaymentAttempt::query()
                        ->where(
                            'idempotency_key',
                            $idempotencyKey
                        )
                        ->first();

                if ($existingAttempt) {
                    if (
                        (int) $existingAttempt->order_id
                            !== (int) $lockedOrder->id
                        || $existingAttempt->provider
                            !== $provider
                    ) {
                        throw new RuntimeException(
                            'Payment idempotency key is already in use for another payment context.'
                        );
                    }

                    return $existingAttempt;
                }

                /*
                 * A failed payment may be retried with a NEW
                 * idempotency key while the order itself remains
                 * pending.
                 *
                 * Paid, cancelled, completed, etc. orders cannot
                 * start another normal payment attempt.
                 */
                if (
                    $lockedOrder->status !== 'pending'
                    || ! in_array(
                        $lockedOrder->payment_status,
                        [
                            'unpaid',
                            'failed',
                        ],
                        true
                    )
                ) {
                    throw new RuntimeException(
                        'Order is not eligible for a new payment attempt.'
                    );
                }

                $merchantReference =
                    'MR-' .
                    strtoupper(
                        Str::uuid()->toString()
                    );

                $attempt =
                    PaymentAttempt::create([
                        'order_id' =>
                            $lockedOrder->id,

                        'provider' =>
                            $provider,

                        'status' =>
                            'initiated',

                        'amount' =>
                            $lockedOrder->total,

                        'currency' =>
                            strtoupper(
                                $lockedOrder->currency
                            ),

                        'idempotency_key' =>
                            $idempotencyKey,

                        'merchant_reference' =>
                            $merchantReference,

                        'request_payload' =>
                            $requestPayload,
                    ]);

                /*
                 * A new retry attempt means the order is waiting
                 * for payment again.
                 */
                $lockedOrder->update([
                    'payment_provider' =>
                        $provider,

                    'payment_status' =>
                        'unpaid',
                ]);

                return $attempt;
            });
        } catch (QueryException $exception) {
            /*
             * If a concurrent request created this idempotency key
             * first, return it only when it belongs to the exact
             * same order/provider context.
             */
            $existingAttempt =
                PaymentAttempt::query()
                    ->where(
                        'idempotency_key',
                        $idempotencyKey
                    )
                    ->first();

            if ($existingAttempt) {
                if (
                    (int) $existingAttempt->order_id
                        !== (int) $order->id
                    || $existingAttempt->provider
                        !== $provider
                ) {
                    throw new RuntimeException(
                        'Payment idempotency key is already in use for another payment context.',
                        previous: $exception
                    );
                }

                return $existingAttempt;
            }

            throw $exception;
        }
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
            /*
             * Canonical lock order:
             * Order first, then attempt.
             */
            $order = Order::query()
                ->whereKey($attempt->order_id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedAttempt =
                PaymentAttempt::query()
                    ->whereKey($attempt->id)
                    ->where(
                        'order_id',
                        $order->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

            if ($lockedAttempt->status === 'paid') {
                return $lockedAttempt->fresh();
            }

            $lockedAttempt->update([
                'status' => 'paid',

                'provider_payment_id' =>
                    $providerPaymentId
                    ?? $lockedAttempt
                        ->provider_payment_id,

                'response_payload' =>
                    $responsePayload,

                'paid_at' => now(),
                'failed_at' => null,
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
            /*
             * Canonical lock order:
             * Order first, then attempt.
             */
            $order = Order::query()
                ->whereKey($attempt->order_id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedAttempt =
                PaymentAttempt::query()
                    ->whereKey($attempt->id)
                    ->where(
                        'order_id',
                        $order->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

            if ($lockedAttempt->status === 'paid') {
                return $lockedAttempt->fresh();
            }

            if ($lockedAttempt->status === 'failed') {
                return $lockedAttempt->fresh();
            }

            $lockedAttempt->update([
                'status' => 'failed',

                'response_payload' =>
                    $responsePayload,

                'failed_at' => now(),
            ]);

            /*
             * The order remains pending but records that its most
             * recent payment attempt failed. createPaymentAttempt()
             * explicitly permits a retry from this state.
             */
            if ($order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'failed',
                ]);
            }

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
                strtoupper(
                    Str::random(8)
                );
        } while (
            Order::query()
                ->where(
                    'order_number',
                    $orderNumber
                )
                ->exists()
        );

        return $orderNumber;
    }
}