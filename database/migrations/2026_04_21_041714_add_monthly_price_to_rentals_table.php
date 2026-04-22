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
        Schema::table('rentals', function (Blueprint $table) {
            $table->integer('monthly_price')->nullable()->after('total_price');
        });

        // Backfill existing rentals with their room's current price
        // Using raw DB if model is not available, but let's try Eloquent first
        \App\Models\Rental::chunk(100, function ($rentals) {
            foreach ($rentals as $rental) {
                if ($rental->room_id) {
                    $room = \Illuminate\Support\Facades\DB::table('rooms')->where('id', $rental->room_id)->first();
                    if ($room) {
                        \Illuminate\Support\Facades\DB::table('rentals')
                            ->where('id', $rental->id)
                            ->update(['monthly_price' => $room->price]);
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('monthly_price');
        });
    }
};
