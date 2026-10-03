<?php

namespace Tests\Feature;

use App\Models\FulfillmentAttempt;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FulfillmentReconciliationCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_reconciles_stale_processing_attempt(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-CMD-STALE-001',
            'status' => 'processing',
            'payment_status' => 'paid',
            'fulfillment_status' => 'processing',
            'subtotal' => '10.00',
            'total' => '10.00',
            'currency' => 'AZN',
            'customer_email' => 'command@example.test',
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => null,
            'product_name' => 'Command Test Product',
            'product_code' => 'CMD-TEST',
            'quantity' => 1,
            'unit_price' => '10.00',
            'total_price' => '10.00',
            'currency' => 'AZN',
            'delivery_data' => [
                'player_id' => 'CMD-PLAYER-1',
            ],
        ]);

        $attempt = FulfillmentAttempt::create([
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'supplier' => 'test-supplier',
            'status' => 'processing',
            'idempotency_key' => 'FUL-CMD-STALE-001',
            'started_at' => now()->subMinutes(20),
        ]);

        $this
            ->artisan(
                'fulfillment:reconcile-stale',
                ['--minutes' => 10]
            )
            ->expectsOutput(
                'Reconciled 1 stale fulfillment attempt(s).'
            )
            ->assertSuccessful();

        $this->assertSame(
            'unknown',
            $attempt->fresh()->status
        );

        $this->assertSame(
            'manual_review',
            $order->fresh()->fulfillment_status
        );
    }

    public function test_command_rejects_invalid_minutes_option(): void
    {
        $this
            ->artisan(
                'fulfillment:reconcile-stale',
                ['--minutes' => 0]
            )
            ->expectsOutput(
                'The --minutes option must be at least 1.'
            )
            ->assertFailed();
    }
}
