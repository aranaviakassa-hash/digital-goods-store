<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            $table->string('provider');
            $table->string('status')->default('initiated');

            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('AZN');

            $table->string('provider_payment_id')->nullable()->index();
            $table->string('idempotency_key')->nullable()->unique();

            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('failed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_attempts');
    }
};