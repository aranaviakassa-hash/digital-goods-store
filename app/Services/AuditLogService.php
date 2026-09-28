<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditLogService
{
    public function log(
        string $event,
        ?Model $auditable = null,
        array $context = []
    ): AuditLog {
        return AuditLog::create([
            'event' => $event,

            'auditable_type' => $auditable
                ? $auditable::class
                : null,

            'auditable_id' => $auditable?->getKey(),

            'user_id' => auth()->id(),

            'ip_address' => app()->runningInConsole()
                ? null
                : request()->ip(),

            'user_agent' => app()->runningInConsole()
                ? null
                : request()->userAgent(),

            'context' => $context,
        ]);
    }
}