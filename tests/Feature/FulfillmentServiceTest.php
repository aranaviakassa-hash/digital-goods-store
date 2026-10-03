<?php

namespace Tests\Feature;

use App\Contracts\SupplierAdapter;
use App\Models\FulfillmentAttempt;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\FulfillmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\TestCase;

class FulfillmentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_supplier_call_runs_outside_service_database_transaction(): void
    {
        [$order] = $this->makeOrderWithItems(1);

        /*
         * RefreshDatabase may already wrap the entire test in a transaction.
         *
         * We therefore compare against the baseline transaction level
         * instead of assuming that the global level is zero.
         */
        $baselineTransactionLevel =
            DB::transactionLevel();

        $supplier = new class implements SupplierAdapter
        {
            public array $levels = [];

            public function fulfill(
                Order $order,
                OrderItem $item,
                string $idempotencyKey
            ): array {
                $this->levels[] =
                    DB::transactionLevel();

                return [
                    'supplier_reference' =>
                        'SUP-OUTSIDE-TX',

                    'response' => [
                        'ok' => true,
                    ],
                ];
            }
        };

        app(FulfillmentService::class)
            ->fulfill(
                $order,
                $supplier,
                'test-supplier'
            );

        $this->assertSame(
            [$baselineTransactionLevel],
            $supplier->levels
        );
    }

    public function test_supplier_failure_becomes_unknown_and_is_not_automatically_retried(): void
    {
        [$order] =
            $this->makeOrderWithItems(1);

        $supplier =
            new class implements SupplierAdapter
            {
                public int $calls = 0;

                public function fulfill(
                    Order $order,
                    OrderItem $item,
                    string $idempotencyKey
                ): array {
                    $this->calls++;

                    throw new RuntimeException(
                        'Supplier timeout.'
                    );
                }
            };

        $service =
            app(FulfillmentService::class);

        try {
            $service->fulfill(
                $order,
                $supplier,
                'test-supplier'
            );

            $this->fail(
                'Expected supplier timeout.'
            );
        } catch (RuntimeException $exception) {
            $this->assertSame(
                'Supplier timeout.',
                $exception->getMessage()
            );
        }

        $attempt =
            FulfillmentAttempt::query()
                ->firstOrFail();

        $this->assertSame(
            'unknown',
            $attempt->status
        );

        $this->assertSame(
            'manual_review',
            $order->fresh()
                ->fulfillment_status
        );

        $this->assertSame(
            1,
            $supplier->calls
        );

        $this->expectException(
            RuntimeException::class
        );

        try {
            $service->fulfill(
                $order->fresh(),
                $supplier,
                'test-supplier'
            );
        } finally {
            $this->assertSame(
                1,
                $supplier->calls
            );
        }
    }

    public function test_stale_processing_attempt_is_moved_to_unknown_manual_review(): void
    {
        [$order, $items] =
            $this->makeOrderWithItems(2);

        $stale =
            FulfillmentAttempt::create([
                'order_id' =>
                    $order->id,

                'order_item_id' =>
                    $items[0]->id,

                'supplier' =>
                    'test-supplier',

                'status' =>
                    'processing',

                'idempotency_key' =>
                    'FUL-STALE-001',

                'started_at' =>
                    now()->subMinutes(20),
            ]);

        $reserved =
            FulfillmentAttempt::create([
                'order_id' =>
                    $order->id,

                'order_item_id' =>
                    $items[1]->id,

                'supplier' =>
                    'test-supplier',

                'status' =>
                    'reserved',

                'idempotency_key' =>
                    'FUL-STALE-002',
            ]);

        $count =
            app(FulfillmentService::class)
                ->reconcileStaleProcessing(10);

        $this->assertSame(
            1,
            $count
        );

        $this->assertSame(
            'unknown',
            $stale->fresh()->status
        );

        $this->assertSame(
            'blocked',
            $reserved->fresh()->status
        );

        $this->assertSame(
            'manual_review',
            $order->fresh()
                ->fulfillment_status
        );

        $this->assertSame(
            'stale_processing_timeout',
            $stale->fresh()
                ->response_payload['reason']
        );
    }

    public function test_recent_processing_attempt_is_not_reconciled_as_stale(): void
    {
        [$order, $items] =
            $this->makeOrderWithItems(1);

        $attempt =
            FulfillmentAttempt::create([
                'order_id' =>
                    $order->id,

                'order_item_id' =>
                    $items[0]->id,

                'supplier' =>
                    'test-supplier',

                'status' =>
                    'processing',

                'idempotency_key' =>
                    'FUL-RECENT-001',

                'started_at' =>
                    now()->subMinutes(2),
            ]);

        $count =
            app(FulfillmentService::class)
                ->reconcileStaleProcessing(10);

        $this->assertSame(
            0,
            $count
        );

        $this->assertSame(
            'processing',
            $attempt->fresh()->status
        );

        $this->assertSame(
            'processing',
            $order->fresh()
                ->fulfillment_status
        );
    }

    public function test_multi_item_fulfillment_uses_deterministic_per_item_keys_and_completes_order(): void
    {
        [$order, $items] =
            $this->makeOrderWithItems(2);

        $baselineTransactionLevel =
            DB::transactionLevel();

        $supplier =
            new class implements SupplierAdapter
            {
                public array $calls = [];

                public function fulfill(
                    Order $order,
                    OrderItem $item,
                    string $idempotencyKey
                ): array {
                    $this->calls[] = [
                        'item_id' =>
                            $item->id,

                        'quantity' =>
                            $item->quantity,

                        'idempotency_key' =>
                            $idempotencyKey,

                        'transaction_level' =>
                            DB::transactionLevel(),
                    ];

                    return [
                        'supplier_reference' =>
                            'SUP-' . $item->id,

                        'response' => [
                            'ok' => true,
                        ],
                    ];
                }
            };

        $result =
            app(FulfillmentService::class)
                ->fulfill(
                    $order,
                    $supplier,
                    'test-supplier'
                );

        $this->assertSame(
            'completed',
            $result->status
        );

        $this->assertSame(
            'fulfilled',
            $result->fulfillment_status
        );

        $this->assertCount(
            2,
            $supplier->calls
        );

        foreach (
            $supplier->calls
            as $index => $call
        ) {
            $item =
                $items[$index];

            $expected =
                'FUL-' .
                strtoupper(
                    substr(
                        hash(
                            'sha256',
                            'test-supplier'
                            . ':'
                            . $order->id
                            . ':'
                            . $item->id
                        ),
                        0,
                        40
                    )
                );

            $this->assertSame(
                $expected,
                $call['idempotency_key']
            );

            /*
             * Supplier call must not run inside an additional
             * FulfillmentService transaction.
             */
            $this->assertSame(
                $baselineTransactionLevel,
                $call['transaction_level']
            );
        }

        $this->assertDatabaseCount(
            'fulfillment_attempts',
            2
        );

        $this->assertSame(
            2,
            FulfillmentAttempt::query()
                ->where(
                    'status',
                    'fulfilled'
                )
                ->count()
        );
    }

    private function makeOrderWithItems(
        int $count
    ): array {
        $order =
            Order::create([
                'order_number' =>
                    'ORD-FUL-' .
                    strtoupper(
                        fake()
                            ->unique()
                            ->bothify(
                                '????####'
                            )
                    ),

                'status' =>
                    'processing',

                'payment_status' =>
                    'paid',

                'fulfillment_status' =>
                    'processing',

                'subtotal' =>
                    0,

                'total' =>
                    0,

                'currency' =>
                    'AZN',

                'customer_email' =>
                    fake()
                        ->unique()
                        ->safeEmail(),
            ]);

        $items = [];

        for (
            $i = 1;
            $i <= $count;
            $i++
        ) {
            $items[] =
                OrderItem::create([
                    'order_id' =>
                        $order->id,

                    'product_id' =>
                        null,

                    'product_name' =>
                        'Test Product ' . $i,

                    'product_code' =>
                        'TEST-' . $i,

                    'quantity' =>
                        $i,

                    'unit_price' =>
                        '10.00',

                    'total_price' =>
                        '10.00',

                    'currency' =>
                        'AZN',

                    'delivery_data' => [
                        'player_id' =>
                            'PLAYER-' . $i,
                    ],
                ]);
        }

        return [
            $order->fresh(),
            $items,
        ];
    }
}