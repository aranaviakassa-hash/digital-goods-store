<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public function log(
        string $event,
        ?Model $auditable = null,
        array $context = []
    ): AuditLog {
        $actorId = Auth::guard('admin')->id()
            ?? Auth::guard('web')->id();

        return AuditLog::create([
            'event' => $event,

            'auditable_type' => $auditable
                ? $auditable::class
                : null,

            'auditable_id' => $auditable?->getKey(),

            'user_id' => $actorId,

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
