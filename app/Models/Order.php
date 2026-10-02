<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentAttempts(): HasMany
    {
        return $this->hasMany(PaymentAttempt::class);
    }

    public function securityReview(): HasOne
    {
        return $this->hasOne(SecurityReview::class);
    }

    public function fulfillmentAttempts(): HasMany
    {
        return $this->hasMany(FulfillmentAttempt::class);
    }

    public function evidence(): HasOne
    {
        return $this->hasOne(OrderEvidence::class);
    }
}