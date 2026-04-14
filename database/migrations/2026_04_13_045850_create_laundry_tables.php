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
        // 1. Laundry Partners Table
        Schema::create('laundries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        // 2. Laundry Services (Pricing list per partner)
        Schema::create('laundry_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laundry_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        // 3. Laundry Orders
        Schema::create('laundry_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('laundry_id')->constrained()->onDelete('cascade');
            $table->json('items'); // e.g. [{"item": "Baju", "qty": 5, "price": 5000}]
            $table->decimal('total_price', 15, 2);
            $table->enum('status', ['pending', 'picked_up', 'in_progress', 'ready', 'delivered', 'done'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laundry_orders');
        Schema::dropIfExists('laundry_services');
        Schema::dropIfExists('laundries');
    }
};
