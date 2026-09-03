<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $adminDistrict = $isSuperAdmin ? null : $user->district;

        $query = Room::latest();

        // Scope to admin's district unless superadmin
        if (!$isSuperAdmin) {
            $query->where('district', $user->district ?? 'NOT_SET');
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%")
                  ->orWhere('room_type', 'like', "%{$search}%")
                  ->orWhere('property_name', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('district') && $isSuperAdmin) {
            $query->where('district', $request->get('district'));
        }

        $rooms = $query->paginate(10);

        // Districts dropdown: superadmin sees all, regional admin sees only their own
        $districtsQuery = Room::select('district', \DB::raw('count(*) as count'))
            ->whereNotNull('district')
            ->groupBy('district')
            ->orderBy('district');

        if (!$isSuperAdmin) {
            $districtsQuery->where('district', $user->district ?? 'NOT_SET');
        }

        $districts = $districtsQuery->get();

        $statsQuery = Room::query();
        if (!$isSuperAdmin) {
            $statsQuery->where('district', $user->district ?? 'NOT_SET');
        }
        $stats = [
            'total'     => (clone $statsQuery)->count(),
            'available' => (clone $statsQuery)->where('status', 'available')->count(),
            'occupied'  => (clone $statsQuery)->where('status', 'occupied')->count(),
        ];

        return view('rooms.index', compact('rooms', 'stats', 'districts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $assets = Asset::all();
        $propertyNames = Room::whereNotNull('property_name')->distinct()->pluck('property_name');
        $adminDistrict = $user->isSuperAdmin() ? null : $user->district;
        return view('rooms.create', compact('assets', 'propertyNames', 'adminDistrict'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'property_name' => 'required|string|max:255',
            'room_number' => 'required|string|max:255|unique:rooms,room_number',
            'room_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'nullable|in:available,occupied,maintenance',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'village' => 'required|string',
            'address' => 'required|string',
            'gender' => 'required|in:putra,putri,gabungan',
            'deposit' => 'nullable|numeric|min:0',
            'additional_rules' => 'nullable|string',
            'assets' => 'nullable|array',
            'assets.*' => 'exists:assets,id',
            'picture' => 'nullable|array',
            'picture.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'discount_label' => 'nullable|string|max:255',
            'discount_start' => 'nullable|date',
            'discount_end' => 'nullable|date|after_or_equal:discount_start',
        ]);

        $picturesPaths = [];
        if ($request->hasFile('picture')) {
            foreach ($request->file('picture') as $file) {
                $path = $file->store('rooms', 'public');
                $picturesPaths[] = $path;
            }
        }

        $validatedData['picture'] = $picturesPaths;
        $validatedData['discount_percentage'] = $validatedData['discount_percentage'] ?? 0;
        $validatedData['status'] = $validatedData['status'] ?? 'available';

        $room = Room::create($validatedData);

        if ($request->has('assets')) {
            $room->assets()->sync($request->assets);
        }

        // For regional admin, lock district to their own district
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $user->district) {
            $room->update(['district' => $user->district]);
        }

        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $user = auth()->user();
        $assets = Asset::all();
        $room->load('assets');
        $propertyNames = Room::whereNotNull('property_name')->distinct()->pluck('property_name');
        $adminDistrict = $user->isSuperAdmin() ? null : $user->district;
        return view('rooms.edit', compact('room', 'assets', 'propertyNames', 'adminDistrict'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validatedData = $request->validate([
            'property_name' => 'required|string|max:255',
            'room_number' => 'required|string|max:255|unique:rooms,room_number,' . $room->id,
            'room_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'village' => 'required|string',
            'address' => 'required|string',
            'gender' => 'required|in:putra,putri,gabungan',
            'deposit' => 'nullable|numeric|min:0',
            'additional_rules' => 'nullable|string',
            'assets' => 'nullable|array',
            'assets.*' => 'exists:assets,id',
            'picture' => 'nullable|array',
            'picture.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'discount_label' => 'nullable|string|max:255',
            'discount_start' => 'nullable|date',
            'discount_end' => 'nullable|date|after_or_equal:discount_start',
        ]);

        $currentPictures = $room->picture ?? [];

        if ($request->hasFile('picture')) {
            $newPicturesPaths = [];
            foreach ($request->file('picture') as $file) {
                $path = $file->store('rooms', 'public');
                $newPicturesPaths[] = $path;
            }
            // Append new pictures to existing ones
            $validatedData['picture'] = array_merge($currentPictures, $newPicturesPaths);
        }
        else {
            $validatedData['picture'] = $currentPictures;
        }

        $validatedData['discount_percentage'] = $validatedData['discount_percentage'] ?? 0;

        $room->update($validatedData);

        // For regional admin, lock district to their own district
        $adminUser = auth()->user();
        if (!$adminUser->isSuperAdmin() && $adminUser->district) {
            $room->update(['district' => $adminUser->district]);
        }

        if ($request->has('assets')) {
            $room->assets()->sync($request->assets);
        } else {
            $room->assets()->detach();
        }

        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    /**
     * Generate a suggested room number based on the property name.
     * e.g. "Ahmad Sadikin" -> prefix "AS", returns "AS01", "AS02", etc.
     */
    public function generateRoomNumber(Request $request)
    {
        $propertyName = $request->query('property_name', '');
        if (!$propertyName) {
            return response()->json(['room_number' => '']);
        }

        // Build initials from each word in the property name
        $words  = preg_split('/\s+/', trim($propertyName));
        $prefix = '';
        foreach ($words as $word) {
            $first = mb_substr($word, 0, 1);
            $prefix .= mb_strtoupper($first);
        }

        // Scope to the current admin's district unless superadmin
        $user  = auth()->user();
        $query = Room::where('room_number', 'like', $prefix . '%');
        if (!$user->isSuperAdmin() && $user->district) {
            $query->where('district', $user->district);
        }

        $existing = $query->pluck('room_number');

        // Find the highest numeric suffix among existing codes with this prefix
        $maxNum = 0;
        foreach ($existing as $num) {
            $suffix = substr($num, strlen($prefix));
            if (is_numeric($suffix)) {
                $maxNum = max($maxNum, (int) $suffix);
            }
        }

        $nextNum    = str_pad($maxNum + 1, 2, '0', STR_PAD_LEFT);
        $roomNumber = $prefix . $nextNum;

        return response()->json(['room_number' => $roomNumber, 'prefix' => $prefix]);
    }

    /**
     * Remove individual image.
     */
    public function destroyImage(Request $request, Room $room)
    {
        $imagePath = $request->image_path;
        $pictures = $room->picture ?? [];

        if (($key = array_search($imagePath, $pictures)) !== false) {
            unset($pictures[$key]);
            
            // Delete from storage
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            $room->update(['picture' => array_values($pictures)]);
        }

        return redirect()->back()->with('success', 'Gambar berhasil dihapus.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        // Delete all pictures from storage
        $pictures = $room->picture ?? [];
        foreach ($pictures as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil dihapus.');
    }
}
