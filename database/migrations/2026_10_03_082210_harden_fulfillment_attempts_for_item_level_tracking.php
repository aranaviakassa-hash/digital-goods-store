<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fulfillment_attempts', function (Blueprint $table) {
            $table->foreignId('order_item_id')
                ->nullable()
                ->after('order_id')
                ->constrained('order_items')
                ->cascadeOnDelete();

            $table->unique(
                ['supplier', 'order_item_id'],
                'fulfillment_attempts_supplier_item_unique'
            );

            $table->index(
                ['order_id', 'status'],
                'fulfillment_attempts_order_status_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('fulfillment_attempts', function (Blueprint $table) {
            $table->dropUnique(
                'fulfillment_attempts_supplier_item_unique'
            );

            $table->dropIndex(
                'fulfillment_attempts_order_status_index'
            );

            $table->dropForeign(['order_item_id']);

            $table->dropColumn('order_item_id');
        });
    }
};