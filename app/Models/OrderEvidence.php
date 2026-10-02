<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderEvidence extends Model
{
    protected $table = 'order_evidence';

    protected $fillable = [
        'order_id',
        'terms_version',
        'refund_policy_version',
        'delivery_policy_version',
        'privacy_policy_version',
        'terms_accepted_at',
        'refund_policy_accepted_at',
        'delivery_policy_accepted_at',
        'customer_data_confirmed_at',
        'ip_address',
        'user_agent',
        'locale',
    ];

    protected function casts(): array
    {
        return [
            'terms_accepted_at' => 'datetime',
            'refund_policy_accepted_at' => 'datetime',
            'delivery_policy_accepted_at' => 'datetime',
            'customer_data_confirmed_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}