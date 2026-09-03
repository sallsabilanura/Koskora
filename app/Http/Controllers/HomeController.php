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
        $groupedRoomsCollection = $allRooms->groupBy(function($item) {
            return $item->property_name ?? 'Unit ' . $item->room_number;
        })->map(function($propertyRooms) {
            $first = $propertyRooms->first();
            $cheapestRoom = $propertyRooms->sortBy(function($room) {
                return $room->discounted_price;
            })->first();

            return (object)[
                'name' => $first->property_name ?? 'Kamar ' . $first->room_number,
                'location' => $first->district . ', ' . $first->city,
                'district' => $first->district,
                'city' => $first->city,
                'min_price' => $cheapestRoom->price,
                'min_discounted_price' => $cheapestRoom->discounted_price,
                'has_discount' => $propertyRooms->contains(function($room) {
                    return $room->hasDiscount();
                }),
                'max_discount' => $propertyRooms->max('discount_percentage'),
                'discount_label' => $propertyRooms->where('discount_percentage', '>', 0)->sortByDesc('discount_percentage')->first()?->discount_label,
                'discount_end' => $propertyRooms->where('discount_percentage', '>', 0)->sortByDesc('discount_percentage')->first()?->discount_end,
                'room_types' => $propertyRooms->pluck('room_type')->unique(),
                'rooms' => $propertyRooms,
                'avg_rating' => $propertyRooms->flatMap->reviews->avg('rating') ?: 5.0,
                'total_reviews' => $propertyRooms->flatMap->reviews->count(),
                'thumbnail' => $first->picture[0] ?? null,
                'gender' => $first->gender,
            ];
        })->values();

        // Paginate the grouped collection manually
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 15;
        $currentPageItems = $groupedRoomsCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $groupedRooms = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $groupedRoomsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        $laundries = Laundry::all();
        $cleaners = Cleaner::with('user')->get();

        // Get unique cities and districts for filters
        $cities = Room::whereNotNull('city')->distinct()->pluck('city');
        $districts = Room::whereNotNull('district')->distinct()->pluck('district');

        return view('welcome', [
            'rooms'        => $allRooms,
            'groupedRooms' => $groupedRooms,
            'laundries'    => $laundries,
            'cleaners'     => $cleaners,
            'cities'       => $cities,
            'districts'    => $districts,
        ]);
    }

    /**
     * Show property detail page (all rooms grouped by property_name).
     */
    public function showProperty($propertyName)
    {
        $propertyName = urldecode($propertyName);

        $rooms = Room::where('property_name', $propertyName)
            ->whereIn('status', ['available', 'occupied'])
            ->with(['assets', 'reviews.user'])
            ->latest()
            ->get();

        if ($rooms->isEmpty()) {
            abort(404);
        }

        $first = $rooms->first();

        $property = (object)[
            'name'          => $first->property_name,
            'location'      => $first->district . ', ' . $first->city,
            'address'       => $first->address,
            'district'      => $first->district,
            'city'          => $first->city,
            'min_price'     => $rooms->sortBy('discounted_price')->first()->price,
            'min_discounted_price' => $rooms->min('discounted_price'),
            'has_discount' => $rooms->contains(function($room) {
                return $room->hasDiscount();
            }),
            'max_discount' => $rooms->max('discount_percentage'),
            'discount_label' => $rooms->where('discount_percentage', '>', 0)->sortByDesc('discount_percentage')->first()?->discount_label,
            'discount_end' => $rooms->where('discount_percentage', '>', 0)->sortByDesc('discount_percentage')->first()?->discount_end,
            'room_types'    => $rooms->pluck('room_type')->unique(),
            'rooms'         => $rooms,
            'avg_rating'    => $rooms->flatMap->reviews->avg('rating') ?: 5.0,
            'total_reviews' => $rooms->flatMap->reviews->count(),
            'thumbnail'     => $first->picture[0] ?? null,
            'gender'        => $first->gender,
        ];

        // Collect unique assets across all rooms
        $allAssets = $rooms->flatMap->assets->unique('id')->values();

        // Collect all reviews across all rooms
        $allReviews = $rooms->flatMap->reviews->sortByDesc('created_at')->values();

        return view('property', compact('property', 'allAssets', 'allReviews'));
    }

    /**
     * Show individual room detail page (public).
     */
    public function showRoom(Room $room)
    {
        $room->load(['assets', 'reviews.user']);

        // Get sibling rooms in same property
        $siblingRooms = collect();
        if ($room->property_name) {
            $siblingRooms = Room::where('property_name', $room->property_name)
                ->where('id', '!=', $room->id)
                ->whereIn('status', ['available', 'occupied'])
                ->with('assets')
                ->limit(6)
                ->get();
        }

        // Determine available date
        $activeRental = \App\Models\Rental::where('room_id', $room->id)
            ->where('status', 'active')
            ->latest('end_date')
            ->first();
        $availableDate = $activeRental ? \Carbon\Carbon::parse($activeRental->end_date)->addDay() : now();

        return view('room_detail', compact('room', 'siblingRooms', 'availableDate'));
    }
}
