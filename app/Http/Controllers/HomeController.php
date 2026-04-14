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
                  ->orWhere('room_type', 'like', "%$s%")
                  ->orWhere('address', 'like', "%$s%");
            });
        }

        $rooms = $query->latest()->get();
        $laundries = Laundry::all();
        $cleaners = Cleaner::with('user')->get();

        // Get unique cities for the filter dropdown
        $cities = Room::whereNotNull('city')->distinct()->pluck('city');

        return view('welcome', compact('rooms', 'laundries', 'cleaners', 'cities'));
    }
}
