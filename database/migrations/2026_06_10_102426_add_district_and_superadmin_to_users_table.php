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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->change();
            $table->string('district')->nullable()->after('role');
            $table->string('province_id')->nullable()->after('district');
            $table->string('province')->nullable()->after('province_id');
            $table->string('city_id')->nullable()->after('province');
            $table->string('city')->nullable()->after('city_id');
            $table->string('district_id')->nullable()->after('city');
            $table->string('village_id')->nullable()->after('district_id');
            $table->string('village')->nullable()->after('village_id');
            $table->text('address')->nullable()->after('village');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user', 'laundry', 'cleaner', 'security'])->default('user')->change();
            $table->dropColumn([
                'district',
                'province_id',
                'province',
                'city_id',
                'city',
                'district_id',
                'village_id',
                'village',
                'address',
            ]);
        });
    }
};
