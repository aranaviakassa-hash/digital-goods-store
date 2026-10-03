<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_attempts', function (Blueprint $table) {
            $table->string('merchant_reference')
                ->nullable()
                ->unique()
                ->after('idempotency_key');
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->dropUnique(['order_id']);

            $table->string('idempotency_key')
                ->nullable()
                ->unique()
                ->after('status');

            $table->unique(
                'payment_attempt_id',
                'refunds_payment_attempt_unique'
            );
        });

        Schema::create('webhook_events', function (Blueprint $table) {
            $table->id();

            $table->string('provider');
            $table->string('event_key');

            $table->string('provider_payment_id')
                ->nullable();

            $table->string('merchant_reference')
                ->nullable();

            $table->string('event_type')
                ->nullable();

            $table->string('processing_status')
                ->default('received');

            $table->json('payload')
                ->nullable();

            $table->string('body_hash', 64);

            $table->string('headers_hash', 64)
                ->nullable();

            $table->text('processing_result')
                ->nullable();

            $table->timestamp('received_at');

            $table->timestamp('processed_at')
                ->nullable();

            $table->timestamps();

            $table->unique(
                ['provider', 'event_key'],
                'webhook_events_provider_event_unique'
            );

            $table->index('provider_payment_id');
            $table->index('merchant_reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_events');

        Schema::table('refunds', function (Blueprint $table) {
            $table->dropUnique(
                'refunds_payment_attempt_unique'
            );

            $table->dropUnique([
                'idempotency_key',
            ]);

            $table->dropColumn(
                'idempotency_key'
            );

            $table->unique('order_id');
        });

        Schema::table('payment_attempts', function (Blueprint $table) {
            $table->dropUnique([
                'merchant_reference',
            ]);

            $table->dropColumn(
                'merchant_reference'
            );
        });
    }
};