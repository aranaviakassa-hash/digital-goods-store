<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityReview extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'risk_score',
        'risk_flags',
        'review_notes',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'risk_score' => 'integer',
            'risk_flags' => 'array',
            'reviewed_at' => 'datetime',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}