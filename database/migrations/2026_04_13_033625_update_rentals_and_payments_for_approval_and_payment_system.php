<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'pending' to rentals status
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('pending', 'active', 'finished') DEFAULT 'pending'");

        // Update rent_payments table
        Schema::table('rent_payments', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('method');
        });

        // Add 'pending' to rent_payments status
        DB::statement("ALTER TABLE rent_payments MODIFY COLUMN status ENUM('unpaid', 'pending', 'paid') DEFAULT 'unpaid'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('active', 'finished') DEFAULT 'active'");

        Schema::table('rent_payments', function (Blueprint $table) {
            $table->dropColumn('payment_proof');
        });

        DB::statement("ALTER TABLE rent_payments MODIFY COLUMN status ENUM('paid', 'unpaid') DEFAULT 'unpaid'");
    }
};
