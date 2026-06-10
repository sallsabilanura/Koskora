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
        Schema::create('security_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Reporter (Satpam)
            $table->string('location');
            $table->string('title');
            $table->text('description');
            $table->dateTime('incident_date');
            $table->enum('status', ['pending', 'resolved'])->default('pending');
            $table->string('attachment')->nullable();
            $table->timestamps();
        });

        Schema::create('security_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Satpam
            $table->string('location'); // e.g. Pasar Minggu
            $table->time('start_time');
            $table->time('end_time');
            $table->date('date');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_shifts');
        Schema::dropIfExists('security_reports');
    }
};
