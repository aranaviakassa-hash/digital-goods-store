<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('category');

            $table->string('supplier')
                ->nullable();

            $table->string('supplier_product_code')
                ->nullable();

            $table->decimal('price', 10, 2);

            $table->string('currency', 10)
                ->default('AZN');

            $table->boolean('is_active')
                ->default(false);

            $table->boolean('resale_verified')
                ->default(false);

            $table->boolean('bank_approved')
                ->default(false);

            $table->text('description')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};