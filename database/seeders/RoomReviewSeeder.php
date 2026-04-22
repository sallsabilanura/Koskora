<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use App\Models\RoomReview;
use Illuminate\Database\Seeder;

class RoomReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comments = [
            'Kamar sangat bersih dan nyaman. Pengelola responsif banget!',
            'Lokasi strategis, dekat transportasi umum. Sangat direkomendasikan.',
            'Fasilitas lengkap, harga sebanding dengan kualitasnya.',
            'Lingkungan tenang dan aman, cocok untuk kerja dari rumah.',
            'Kamar mandi bersih, air panas tersedia. Puas banget!',
            'Pengelola ramah, masalah cepat ditangani. 5 bintang!',
            'Kamar luas, pencahayaan bagus. Worth the price.',
            'Wifi kencang, listrik stabil. Kos impian anak kost!',
        ];

        $rooms = Room::all();
        $users = User::where('role', 'user')->get();

        if ($users->isEmpty()) {
            $this->command->warn('No users with role "user" found. Skipping review seeder.');
            return;
        }

        foreach ($rooms as $room) {
            // Only seed if room has no reviews yet
            if ($room->reviews()->count() > 0) {
                continue;
            }

            // Add 1-3 reviews per room
            $reviewCount = rand(1, min(3, $users->count()));
            $selectedUsers = $users->random($reviewCount);

            foreach ($selectedUsers as $user) {
                RoomReview::create([
                    'room_id'      => $room->id,
                    'user_id'      => $user->id,
                    'rating'       => rand(4, 5),
                    'comment'      => $comments[array_rand($comments)],
                    'is_anonymous' => rand(0, 1),
                ]);
            }
        }

        $this->command->info('Room reviews seeded successfully.');
    }
}
