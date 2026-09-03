<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 'active', 'pending', 'rejected'
            $table->string('status')->default('active')->after('role');
        });

        // Set existing superadmin accounts to active (they are already approved)
        // Set existing user/admin accounts registered via public form to pending
        // We keep existing ones as 'active' to not break current functionality
        // Only newly registered accounts will be set to 'pending'
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
