<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table
                ->boolean('catalog_visible')
                ->default(false)
                ->after('is_active');

            $table->index(
                ['catalog_visible', 'is_active'],
                'products_catalog_visible_active_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(
                'products_catalog_visible_active_index'
            );

            $table->dropColumn(
                'catalog_visible'
            );
        });
    }
};