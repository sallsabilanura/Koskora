<?php

namespace App\Http\Controllers;

use App\Models\RentPayment;
use App\Models\Rental;
use App\Models\Room;
use App\Models\Tenants;
use Illuminate\Http\Request;

class RentPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = RentPayment::with(['rental', 'room', 'tenants'])->latest()->paginate(10);
        return view('rent_payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rentals = Rental::where('status', 'active')->with(['tenant', 'room'])->get();
        return view('rent_payments.create', compact('rentals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'month' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:paid,unpaid',
            'method' => 'required|string|max:255',
        ]);

        $rental = Rental::findOrFail($validatedData['rental_id']);

        $validatedData['room_id'] = $rental->room_id;
        $validatedData['tenants_id'] = $rental->tenant_id;

        RentPayment::create($validatedData);

        return redirect()->route('rent-payments.index')
            ->with('success', 'Pembayaran berhasil dicatat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RentPayment $rentPayment)
    {
        $rentPayment->load(['rental', 'room', 'tenants']);
        return view('rent_payments.show', compact('rentPayment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RentPayment $rentPayment)
    {
        $rentals = Rental::with(['tenant', 'room'])->get();
        return view('rent_payments.edit', compact('rentPayment', 'rentals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RentPayment $rentPayment)
    {
        $validatedData = $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'month' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:paid,unpaid',
            'method' => 'required|string|max:255',
        ]);

        $rental = Rental::findOrFail($validatedData['rental_id']);

        $validatedData['room_id'] = $rental->room_id;
        $validatedData['tenants_id'] = $rental->tenant_id;

        $rentPayment->update($validatedData);

        return redirect()->route('rent-payments.index')
            ->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RentPayment $rentPayment)
    {
        // Delete verification file if exists
        if ($rentPayment->payment_proof) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($rentPayment->payment_proof);
        }

        $rentPayment->delete();

        return redirect()->route('rent-payments.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }

    /**
     * Show form for user to submit payment confirmation.
     */
    public function userCreate(Request $request)
    {
        $rental = Rental::with('room')->findOrFail($request->query('rental_id'));
        
        // Generate list of months (Current + 3 months ahead)
        $months = [];
        for ($i = 0; $i < 4; $i++) {
            $months[] = now()->addMonths($i)->format('F Y');
        }

        return view('rent_payments.user_create', compact('rental', 'months'));
    }

    /**
     * Store user payment confirmation.
     */
    public function userStore(Request $request)
    {
        $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'month' => 'required|string',
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'method' => 'required|string',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $rental = Rental::findOrFail($request->rental_id);

        // Prevent duplicate payments for the same month (if already paid or pending)
        $exists = RentPayment::where('tenants_id', $rental->tenant_id)
            ->where('month', $request->month)
            ->whereIn('status', ['paid', 'pending'])
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['month' => 'Pembayaran untuk bulan ini sudah ada atau sedang menunggu verifikasi.'])->withInput();
        }

        $data = [
            'rental_id' => $rental->id,
            'room_id' => $rental->room_id,
            'tenants_id' => $rental->tenant_id,
            'month' => $request->month,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'status' => 'pending',
            'method' => $request->method,
        ];

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payments', 'public');
            $data['payment_proof'] = $path;
        }

        RentPayment::create($data);

        return redirect()->route('dashboard')->with('success', 'Bukti pembayaran berhasil dikirim! Admin akan segera memverifikasi.');
    }

    /**
     * Verify the payment.
     */
    public function verify(RentPayment $rentPayment)
    {
        if ($rentPayment->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pembayaran berstatus pending yang dapat diverifikasi.');
        }

        $rentPayment->update(['status' => 'paid', 'rejection_reason' => null]);

        return redirect()->route('rent-payments.index')->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Reject the payment.
     */
    public function reject(Request $request, RentPayment $rentPayment)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        if ($rentPayment->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pembayaran berstatus pending yang dapat ditolak.');
        }

        $rentPayment->update([
            'status' => 'unpaid',
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->route('rent-payments.index')->with('success', 'Pembayaran telah ditolak dan alasan telah dikirim ke penyewa.');
    }

    /**
     * Display a listing of the user's own payments.
     */
    public function myPayments()
    {
        $user = auth()->user();
        $tenant = $user->tenant;

        if (!$tenant) {
            return redirect()->route('dashboard')->with('error', 'Data tenant tidak ditemukan.');
        }

        // Get active rental to check current bill
        $activeRental = Rental::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->first();

        $currentPaymentStatus = 'unpaid';
        $currentPayment = null;
        if ($activeRental) {
            $currentPayment = RentPayment::where('tenants_id', $tenant->id)
                ->where('month', date('F Y'))
                ->first();
            
            if ($currentPayment) {
                $currentPaymentStatus = $currentPayment->status;
            }
        }

        $myPayments = RentPayment::where('tenants_id', $tenant->id)
            ->with(['room', 'rental'])
            ->orderBy('payment_date', 'desc')
            ->get();

        return view('rent_payments.user_index', compact(
            'tenant', 
            'activeRental', 
            'currentPaymentStatus', 
            'currentPayment', 
            'myPayments'
        ));
    }

    /**
     * Show the ticket for a verified payment.
     */
    public function showTicket(RentPayment $rentPayment)
    {
        // Security check
        if ($rentPayment->tenants_id !== auth()->user()->tenant->id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($rentPayment->status !== 'paid') {
            return redirect()->back()->with('error', 'Tiket belum tersedia karena pembayaran belum diverifikasi.');
        }

        return view('rent_payments.ticket', compact('rentPayment'));
    }
}
