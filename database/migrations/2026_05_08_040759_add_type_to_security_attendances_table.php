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
        Schema::table('security_attendances', function (Blueprint $table) {
            $table->enum('type', ['in', 'out'])->default('in')->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('security_attendances', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
