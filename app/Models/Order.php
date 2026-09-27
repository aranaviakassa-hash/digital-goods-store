<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'idempotency_key',
        'user_id',
        'status',
        'payment_status',
        'fulfillment_status',
        'subtotal',
        'total',
        'currency',
        'customer_email',
        'customer_name',
        'payment_provider',
        'payment_reference',
        'supplier_reference',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (blank($order->order_number)) {
                do {
                    $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));
                } while (static::where('order_number', $orderNumber)->exists());

                $order->order_number = $orderNumber;
            }
        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}