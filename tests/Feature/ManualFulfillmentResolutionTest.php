<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\FulfillmentAttempt;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\ManualFulfillmentResolutionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManualFulfillmentResolutionTest extends TestCase
{
    use RefreshDatabase;

    public function test_unknown_attempt_can_be_confirmed_fulfilled_and_blocked_work_resumes(): void
    {
        [$order, $item] = $this->makeOrderAndItem();

        $unknown = FulfillmentAttempt::create([
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'supplier' => 'test-supplier',
            'status' => 'unknown',
            'idempotency_key' => 'FUL-MANUAL-001',
            'response_payload' => ['error' => 'timeout'],
            'failed_at' => now(),
        ]);

        $secondItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => null,
            'product_name' => 'Second item',
            'product_code' => 'SKU-2',
            'quantity' => 1,
            'unit_price' => '5.00',
            'total_price' => '5.00',
            'currency' => 'AZN',
        ]);

        $blocked = FulfillmentAttempt::create([
            'order_id' => $order->id,
            'order_item_id' => $secondItem->id,
            'supplier' => 'test-supplier',
            'status' => 'blocked',
            'idempotency_key' => 'FUL-MANUAL-002',
        ]);

        app(ManualFulfillmentResolutionService::class)
            ->resolveAsFulfilled(
                $unknown,
                'SUPPLIER-CONFIRMED-1',
                'Supplier portal confirms delivery.'
            );

        $this->assertSame('fulfilled', $unknown->fresh()->status);
        $this->assertSame(
            'SUPPLIER-CONFIRMED-1',
            $unknown->fresh()->supplier_reference
        );
        $this->assertSame('reserved', $blocked->fresh()->status);
        $this->assertSame('processing', $order->fresh()->fulfillment_status);

        $this->assertDatabaseHas('audit_logs', [
            'event' => 'fulfillment.manual_resolved_fulfilled',
            'auditable_id' => $order->id,
        ]);
    }

    public function test_unknown_attempt_can_be_confirmed_failed_without_automatic_retry(): void
    {
        [$order, $item] = $this->makeOrderAndItem();

        $unknown = FulfillmentAttempt::create([
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'supplier' => 'test-supplier',
            'status' => 'unknown',
            'idempotency_key' => 'FUL-MANUAL-FAILED-001',
            'response_payload' => ['error' => 'timeout'],
            'failed_at' => now(),
        ]);

        app(ManualFulfillmentResolutionService::class)
            ->resolveAsFailed(
                $unknown,
                'Supplier confirms the top-up was not delivered.'
            );

        $resolved = $unknown->fresh();

        $this->assertSame('failed', $resolved->status);
        $this->assertSame('manual_review', $order->fresh()->fulfillment_status);
        $this->assertSame(
            'failed',
            $resolved->response_payload['manual_resolution']['outcome']
        );

        $this->assertTrue(
            AuditLog::query()
                ->where('event', 'fulfillment.manual_resolved_failed')
                ->where('auditable_id', $order->id)
                ->exists()
        );
    }

    private function makeOrderAndItem(): array
    {
        $order = Order::create([
            'order_number' => 'ORD-MANUAL-' . uniqid(),
            'status' => 'processing',
            'payment_status' => 'paid',
            'fulfillment_status' => 'manual_review',
            'subtotal' => '10.00',
            'total' => '10.00',
            'currency' => 'AZN',
            'customer_email' => 'manual@example.com',
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => null,
            'product_name' => 'Manual resolution item',
            'product_code' => 'SKU-1',
            'quantity' => 1,
            'unit_price' => '10.00',
            'total_price' => '10.00',
            'currency' => 'AZN',
        ]);

        return [$order, $item];
    }
}
