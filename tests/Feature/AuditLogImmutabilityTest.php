<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LogicException;
use Tests\TestCase;

class AuditLogImmutabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_log_cannot_be_updated(): void
    {
        $log = AuditLog::create([
            'event' => 'test.created',
            'context' => [
                'source' => 'test',
            ],
        ]);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(
            'Audit logs are immutable and cannot be updated.'
        );

        $log->update([
            'event' => 'test.changed',
        ]);
    }

    public function test_audit_log_cannot_be_deleted(): void
    {
        $log = AuditLog::create([
            'event' => 'test.created',
            'context' => [
                'source' => 'test',
            ],
        ]);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(
            'Audit logs are immutable and cannot be deleted.'
        );

        $log->delete();
    }
}
