<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('payment_attempt_id')
                ->nullable()
                ->constrained('payment_attempts')
                ->nullOnDelete();

            $table->string('provider')->nullable();

            $table->string('status')
                ->default('required');

            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);

            $table->string('provider_refund_id')
                ->nullable()
                ->index();

            $table->text('reason')->nullable();

            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();

            $table->timestamp('requested_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamp('failed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};