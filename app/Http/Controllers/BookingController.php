<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenants;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function rent(Room $room)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please log in to rent a room.');
        }

        $user = Auth::user();

        // Check if user is already a tenant
        if (!$user->tenant) {
            return redirect()->route('bookings.complete-profile', ['room_id' => $room->id]);
        }

        // If already a tenant, show confirmation page or create booking request
        // For simplicity as requested: "otomatis juga masuk di table tenants"
        // We will show a simple confirmation page first.
        return view('bookings.confirm', compact('room', 'user'));
    }

    public function completeProfile(Request $request)
    {
        $room_id = $request->query('room_id');
        $room = Room::findOrFail($room_id);
        return view('bookings.complete-profile', compact('room'));
    }

    public function storeProfile(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:20|unique:tenants,nik',
            'occupation' => 'required|string|max:100',
            'emergency_contact' => 'required|string|max:20',
            'address' => 'required|string',
            'room_id' => 'required|exists:rooms,id',
        ]);

        $user = Auth::user();

        // Create Tenant record
        Tenants::create([
            'user_id' => $user->id,
            'nik' => $request->nik,
            'occupation' => $request->occupation,
            'emergency_contact' => $request->emergency_contact,
            'address' => $request->address,
            'status' => 'active',
        ]);

        return redirect()->route('bookings.confirm', ['room' => $request->room_id]);
    }

    public function confirm(Room $room)
    {
        $user = Auth::user();
        return view('bookings.confirm', compact('room', 'user'));
    }

    public function store(Request $request, Room $room)
    {
        $user = Auth::user();
        
        // Auto-create Rental record as PENDING
        Rental::create([
            'tenant_id' => $user->tenant->id,
            'room_id' => $room->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(), // Default 1 month
            'total_price' => $room->price,
            'status' => 'pending',
        ]);

        // Room status stays 'available' until admin approves (ACC)

        return redirect()->route('dashboard')->with('success', 'Permintaan sewa berhasil dikirim! Mohon tunggu persetujuan (ACC) dari Admin.');
    }
}
