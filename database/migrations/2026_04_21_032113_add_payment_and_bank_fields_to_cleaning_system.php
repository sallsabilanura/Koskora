<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cleaners', function (Blueprint $table) {
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
        });

        Schema::table('cleaning_orders', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'pending', 'paid'])->default('unpaid')->after('total_price');
            $table->string('payment_proof')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cleaners', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'account_number', 'account_name']);
        });

        Schema::table('cleaning_orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_proof']);
        });
    }
};
