<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_attempts', function (Blueprint $table) {
            $table->unique(
                ['provider', 'provider_payment_id'],
                'payment_attempts_provider_payment_unique'
            );
        });

        Schema::table('security_reviews', function (Blueprint $table) {
            $table->unique(
                'order_id',
                'security_reviews_order_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('payment_attempts', function (Blueprint $table) {
            $table->dropUnique(
                'payment_attempts_provider_payment_unique'
            );
        });

        Schema::table('security_reviews', function (Blueprint $table) {
            $table->dropUnique(
                'security_reviews_order_unique'
            );
        });
    }
};