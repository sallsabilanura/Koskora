<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Laundry;
use App\Models\Cleaner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::whereIn('status', ['available', 'occupied']);

        if ($request->has('city') && $request->city != '') {
            $query->where('city', $request->city);
        }
        
        if ($request->has('district') && $request->district != '') {
            $query->where('district', $request->district);
        }

        if ($request->has('search') && $request->search != '') {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('room_number', 'like', "%$s%")
                  ->orWhere('property_name', 'like', "%$s%")
                  ->orWhere('room_type', 'like', "%$s%")
                  ->orWhere('address', 'like', "%$s%");
            });
        }

        $allRooms = $query->with(['assets', 'reviews'])->latest()->get();

        // Group rooms by Property
        // We'll group by property_name. If null, we treat each room as its own property for now.
        $groupedRooms = $allRooms->groupBy(function($item) {
            return $item->property_name ?? 'Unit ' . $item->room_number;
        })->map(function($propertyRooms) {
            $first = $propertyRooms->first();
            return (object)[
                'name' => $first->property_name ?? 'Kamar ' . $first->room_number,
                'location' => $first->district . ', ' . $first->city,
                'district' => $first->district,
                'city' => $first->city,
                'min_price' => $propertyRooms->min('price'),
                'room_types' => $propertyRooms->pluck('room_type')->unique(),
                'rooms' => $propertyRooms,
                'avg_rating' => $propertyRooms->flatMap->reviews->avg('rating') ?: 5.0,
                'total_reviews' => $propertyRooms->flatMap->reviews->count(),
                'thumbnail' => $first->picture[0] ?? null,
                'gender' => $first->gender,
            ];
        });

        $laundries = Laundry::all();
        $cleaners = Cleaner::with('user')->get();

        // Get unique cities and districts for filters
        $cities = Room::whereNotNull('city')->distinct()->pluck('city');

        return view('welcome', [
            'rooms'        => $allRooms,
            'groupedRooms' => $groupedRooms,
            'laundries'    => $laundries,
            'cleaners'     => $cleaners,
            'cities'       => $cities,
        ]);
    }
}
