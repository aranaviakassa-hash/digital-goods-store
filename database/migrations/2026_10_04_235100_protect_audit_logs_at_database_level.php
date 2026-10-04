<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::unprepared(<<<'SQL'
            CREATE OR REPLACE FUNCTION playcharge_prevent_audit_log_mutation()
            RETURNS trigger
            LANGUAGE plpgsql
            AS $$
            BEGIN
                RAISE EXCEPTION 'audit_logs is append-only';
            END;
            $$;

            DROP TRIGGER IF EXISTS audit_logs_immutable ON audit_logs;

            CREATE TRIGGER audit_logs_immutable
            BEFORE UPDATE OR DELETE OR TRUNCATE ON audit_logs
            FOR EACH STATEMENT
            EXECUTE FUNCTION playcharge_prevent_audit_log_mutation();
        SQL);
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::unprepared(<<<'SQL'
            DROP TRIGGER IF EXISTS audit_logs_immutable ON audit_logs;
            DROP FUNCTION IF EXISTS playcharge_prevent_audit_log_mutation();
        SQL);
    }
};
