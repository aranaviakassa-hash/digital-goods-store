<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_code',
        'quantity',
        'unit_price',
        'total_price',
        'currency',
        'delivery_data',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'delivery_data' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (OrderItem $orderItem) {
            $orderItem->total_price = round(
                (float) $orderItem->quantity * (float) $orderItem->unit_price,
                2
            );
        });

        static::saved(function (OrderItem $orderItem) {
            $orderItem->recalculateOrderTotals();
        });

        static::deleted(function (OrderItem $orderItem) {
            $orderItem->recalculateOrderTotals();
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function recalculateOrderTotals(): void
    {
        $order = $this->order;

        if (! $order) {
            return;
        }

        $total = $order->items()->sum('total_price');

        $order->update([
            'subtotal' => $total,
            'total' => $total,
        ]);
    }
}