<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LogicException;

class AuditLog extends Model
{
    protected $fillable = [
        'event',
        'auditable_type',
        'auditable_id',
        'user_id',
        'ip_address',
        'user_agent',
        'context',
    ];

    protected function casts(): array
    {
        return [
            'context' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (): void {
            throw new LogicException(
                'Audit logs are immutable and cannot be updated.'
            );
        });

        static::deleting(function (): void {
            throw new LogicException(
                'Audit logs are immutable and cannot be deleted.'
            );
        });
    }

    public function auditable()
    {
        return $this->morphTo();
    }
}
