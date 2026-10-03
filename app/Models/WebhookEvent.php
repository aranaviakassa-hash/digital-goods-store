<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookEvent extends Model
{
    protected $fillable = [
        'provider',
        'event_key',
        'provider_payment_id',
        'merchant_reference',
        'event_type',
        'processing_status',
        'payload',
        'body_hash',
        'headers_hash',
        'processing_result',
        'received_at',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'received_at' => 'datetime',
            'processed_at' => 'datetime',
        ];
    }
}