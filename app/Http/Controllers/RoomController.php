<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::latest()->get();
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assets = Asset::all();
        $propertyNames = Room::whereNotNull('property_name')->distinct()->pluck('property_name');
        return view('rooms.create', compact('assets', 'propertyNames'));
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
            'status' => 'required|in:available,occupied,maintenance',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'village' => 'required|string',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'gender' => 'required|in:putra,putri,gabungan',
            'assets' => 'nullable|array',
            'assets.*' => 'exists:assets,id',
            'picture' => 'nullable|array',
            'picture.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $picturesPaths = [];
        if ($request->hasFile('picture')) {
            foreach ($request->file('picture') as $file) {
                $path = $file->store('rooms', 'public');
                $picturesPaths[] = $path;
            }
        }

        $validatedData['picture'] = $picturesPaths;

        $room = Room::create($validatedData);

        if ($request->has('assets')) {
            $room->assets()->sync($request->assets);
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
        $assets = Asset::all();
        $room->load('assets');
        $propertyNames = Room::whereNotNull('property_name')->distinct()->pluck('property_name');
        return view('rooms.edit', compact('room', 'assets', 'propertyNames'));
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
            'description' => 'nullable|string',
            'gender' => 'required|in:putra,putri,gabungan',
            'assets' => 'nullable|array',
            'assets.*' => 'exists:assets,id',
            'picture' => 'nullable|array',
            'picture.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
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

        $room->update($validatedData);

        if ($request->has('assets')) {
            $room->assets()->sync($request->assets);
        } else {
            $room->assets()->detach();
        }

        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil diperbarui.');
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
