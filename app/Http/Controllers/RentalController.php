<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rentals = Rental::with(['tenant', 'room'])->latest()->get();
        return view('rentals.index', compact('rentals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tenants = \App\Models\Tenants::all();
        $rooms = \App\Models\Room::where('status', 'available')->get();
        return view('rentals.create', compact('tenants', 'rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'duration_months' => 'required|integer|min:1',
            'status' => 'required|in:active,finished',
        ]);

        $room = \App\Models\Room::findOrFail($validatedData['room_id']);
        $startDate = \Carbon\Carbon::parse($validatedData['start_date']);
        $endDate = $startDate->copy()->addMonths($validatedData['duration_months']);

        $validatedData['end_date'] = $endDate;
        $validatedData['total_price'] = $room->price * $validatedData['duration_months'];
        unset($validatedData['duration_months']);

        Rental::create($validatedData);

        // Update room status to occupied if rental is active
        if ($validatedData['status'] == 'active') {
            $room = \App\Models\Room::find($validatedData['room_id']);
            if ($room) {
                $room->update(['status' => 'occupied']);
            }
        }

        return redirect()->route('rentals.index')->with('success', 'Data sewa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rental $rental)
    {
        $rental->load(['tenant', 'room']);
        return view('rentals.show', compact('rental'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rental $rental)
    {
        $tenants = \App\Models\Tenants::all();
        $rooms = \App\Models\Room::all();
        return view('rentals.edit', compact('rental', 'tenants', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rental $rental)
    {
        $validatedData = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'duration_months' => 'required|integer|min:1',
            'status' => 'required|in:active,finished',
        ]);

        $room = \App\Models\Room::findOrFail($validatedData['room_id']);
        $startDate = \Carbon\Carbon::parse($validatedData['start_date']);
        $endDate = $startDate->copy()->addMonths($validatedData['duration_months']);

        $validatedData['end_date'] = $endDate;
        $validatedData['total_price'] = $room->price * $validatedData['duration_months'];
        unset($validatedData['duration_months']);

        $oldRoomId = $rental->room_id;
        $oldStatus = $rental->status;

        $rental->update($validatedData);

        // Handle room status updates
        if ($oldRoomId != $validatedData['room_id'] || $oldStatus != $validatedData['status']) {
            $oldRoom = \App\Models\Room::find($oldRoomId);
            if ($oldRoom)
                $oldRoom->update(['status' => 'available']);

            if ($validatedData['status'] == 'active') {
                $newRoom = \App\Models\Room::find($validatedData['room_id']);
                if ($newRoom)
                    $newRoom->update(['status' => 'occupied']);
            }
        }
        elseif ($validatedData['status'] == 'finished' && $oldStatus == 'active') {
            $room = \App\Models\Room::find($validatedData['room_id']);
            if ($room)
                $room->update(['status' => 'available']);
        }

        return redirect()->route('rentals.index')->with('success', 'Data sewa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rental $rental)
    {
        if ($rental->status == 'active' || $rental->status == 'pending') {
            $room = \App\Models\Room::find($rental->room_id);
            if ($room) {
                $room->update(['status' => 'available']);
            }
        }

        $rental->delete();

        return redirect()->route('rentals.index')->with('success', 'Data sewa berhasil dihapus.');
    }

    /**
     * Approve the pending rental.
     */
    public function approve(Rental $rental)
    {
        if ($rental->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya penyewaan berstatus pending yang dapat disetujui.');
        }

        // Check if room is still available
        if ($rental->room->status !== 'available') {
            return redirect()->back()->with('error', 'Kamar sudah tidak tersedia.');
        }

        // Update rental status
        $rental->update(['status' => 'active']);

        // Update room status
        $rental->room->update(['status' => 'occupied']);

        return redirect()->route('rentals.index')->with('success', 'Penyewaan berhasil disetujui (ACC).');
    }
}
