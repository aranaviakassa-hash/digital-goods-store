<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fulfillment_attempts', function (Blueprint $table) {
            $table->timestamp('started_at')
                ->nullable()
                ->after('response_payload');

            $table->index(
                ['status', 'started_at'],
                'fulfillment_attempts_status_started_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('fulfillment_attempts', function (Blueprint $table) {
            $table->dropIndex(
                'fulfillment_attempts_status_started_index'
            );

            $table->dropColumn('started_at');
        });
    }
};