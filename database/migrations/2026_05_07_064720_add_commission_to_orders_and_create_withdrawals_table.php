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
        Schema::table('laundry_orders', function (Blueprint $table) {
            $table->decimal('commission_amount', 15, 2)->default(0)->after('total_price');
            $table->decimal('partner_amount', 15, 2)->default(0)->after('commission_amount');
        });

        Schema::table('cleaning_orders', function (Blueprint $table) {
            $table->decimal('commission_amount', 15, 2)->default(0)->after('total_price');
            $table->decimal('partner_amount', 15, 2)->default(0)->after('commission_amount');
        });

        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('payment_proof')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
        
        Schema::table('laundry_orders', function (Blueprint $table) {
            $table->dropColumn(['commission_amount', 'partner_amount']);
        });

        Schema::table('cleaning_orders', function (Blueprint $table) {
            $table->dropColumn(['commission_amount', 'partner_amount']);
        });
    }
};
