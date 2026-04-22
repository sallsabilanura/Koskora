<?php

use App\Models\Room;
use App\Models\User;
use App\Models\RoomReview;

// Update existing rooms to have properties
$rooms = Room::all();
$properties = ['Kos Kalibata City', 'Kos Tebet Indah', 'Pasar Minggu Residence', 'Kemang House'];
$districts = ['Kalibata', 'Tebet', 'Pasar Minggu', 'Kemang'];
$cities = ['Jakarta Selatan', 'Jakarta Selatan', 'Jakarta Selatan', 'Jakarta Selatan'];

foreach ($rooms as $index => $room) {
    if (empty($room->property_name)) {
        $pIndex = $index % count($properties);
        $room->update([
            'property_name' => $properties[$pIndex],
            'district' => $districts[$pIndex],
            'city' => $cities[$pIndex],
        ]);
    }
}

// Add some reviews
$users = User::all();
if ($users->count() > 0) {
    foreach ($rooms as $room) {
        if ($room->reviews()->count() == 0) {
            RoomReview::create([
                'room_id' => $room->id,
                'user_id' => $users->random()->id,
                'rating' => rand(4, 5),
                'comment' => 'Kamar sangat bersih dan nyaman. Pelayanan okey banget!',
                'is_anonymous' => rand(0, 1)
            ]);
        }
    }
} Greenland
