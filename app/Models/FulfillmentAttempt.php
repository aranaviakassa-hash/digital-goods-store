<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FulfillmentAttempt extends Model
{
    protected $fillable = [
        'order_id',
        'supplier',
        'status',
        'supplier_reference',
        'idempotency_key',
        'request_payload',
        'response_payload',
        'fulfilled_at',
        'failed_at',
    ];

    protected function casts(): array
    {
        return [
            'request_payload' => 'array',
            'response_payload' => 'array',
            'fulfilled_at' => 'datetime',
            'failed_at' => 'datetime',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}