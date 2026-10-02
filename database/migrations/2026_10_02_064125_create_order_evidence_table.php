<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_evidence', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('terms_version', 50);
            $table->string('refund_policy_version', 50);
            $table->string('delivery_policy_version', 50);
            $table->string('privacy_policy_version', 50);

            $table->timestampTz('terms_accepted_at');
            $table->timestampTz('refund_policy_accepted_at');
            $table->timestampTz('delivery_policy_accepted_at');
            $table->timestampTz('customer_data_confirmed_at');

            $table->string('ip_address', 45)
                ->nullable();

            $table->text('user_agent')
                ->nullable();

            $table->string('locale', 10)
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_evidence');
    }
};