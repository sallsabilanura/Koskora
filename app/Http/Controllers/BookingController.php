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
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:100',
            'nik' => 'required|string|size:16|unique:tenants,nik',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'nomor_whatsapp' => 'required|string|max:20',
            'alamat_ktp' => 'required|string',
            'address' => 'required|string',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'village' => 'required|string|max:100',
            'occupation' => 'required|string|max:100',
            'emergency_contact' => 'required|string|max:20',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_diri' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'room_id' => 'required|exists:rooms,id',
        ]);

        $user = Auth::user();

        $data = $request->except(['foto_ktp', 'foto_diri', 'room_id']);
        $data['user_id'] = $user->id;
        $data['status'] = 'active';

        if ($request->hasFile('foto_ktp')) {
            $data['foto_ktp'] = $request->file('foto_ktp')->store('tenants/ktp', 'public');
        }

        if ($request->hasFile('foto_diri')) {
            $data['foto_diri'] = $request->file('foto_diri')->store('tenants/self', 'public');
        }

        // Create Tenant record
        Tenants::create($data);

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
        
        $request->validate([
            'duration_type' => 'required|in:monthly,yearly',
        ]);

        $durationType = $request->input('duration_type', 'monthly');
        $startDate = now();
        $monthlyPrice = $room->price;

        if ($durationType === 'yearly') {
            $endDate = $startDate->copy()->addYear();
            $monthlyPrice = $room->price * 0.9; // 10% discount
            $totalPrice = $monthlyPrice * 12;
        } else {
            $endDate = $startDate->copy()->addMonth();
            $totalPrice = $room->price;
        }

        // Auto-create Rental record as PENDING
        Rental::create([
            'tenant_id' => $user->tenant->id,
            'room_id' => $room->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'monthly_price' => $monthlyPrice,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'duration_type' => $durationType,
        ]);

        // Room status stays 'available' until admin approves (ACC)

        return redirect()->route('dashboard')->with('success', 'Permintaan sewa berhasil dikirim! Mohon tunggu persetujuan (ACC) dari Admin.');
    }
}
