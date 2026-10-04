<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('resale_verification_reference')->nullable();
            $table->string('bank_approval_reference')->nullable();
            $table->text('approval_notes')->nullable();
            $table->timestamp('resale_verified_at')->nullable();
            $table->timestamp('bank_approved_at')->nullable();
            $table->foreignId('resale_verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('bank_approved_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('resale_verified_by');
            $table->dropConstrainedForeignId('bank_approved_by');
            $table->dropColumn([
                'resale_verification_reference',
                'bank_approval_reference',
                'approval_notes',
                'resale_verified_at',
                'bank_approved_at',
            ]);
        });
    }
};
