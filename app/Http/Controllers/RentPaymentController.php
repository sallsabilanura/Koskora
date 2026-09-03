<?php

namespace App\Http\Controllers;

use App\Models\RentPayment;
use App\Models\Rental;
use App\Models\Room;
use App\Models\Tenants;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class RentPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();

        $query = RentPayment::with(['rental', 'room', 'tenants.user'])->latest();

        // Scope to admin's district via room
        if (!$isSuperAdmin) {
            $safeDistrict = $user->district ?? 'NOT_SET';
            $query->whereHas('room', function ($q) use ($safeDistrict) {
                $q->where('district', $safeDistrict);
            });
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('month', 'like', "%{$search}%")
                  ->orWhere('method', 'like', "%{$search}%")
                  ->orWhereHas('tenants.user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('room', function($rq) use ($search) {
                      $rq->where('room_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tenant_id')) {
            $query->where('tenants_id', $request->tenant_id);
        }

        $payments = $query->paginate(10);

        // Get tenants for filter dropdown, scoped to admin's district
        $tenantQuery = Tenants::with('user')->whereHas('rentPayments');
        if (!$isSuperAdmin) {
            $safeDistrict = $user->district ?? 'NOT_SET';
            $tenantQuery->whereHas('rentals.room', function ($q) use ($safeDistrict) {
                $q->where('district', $safeDistrict);
            });
        }
        $tenants = $tenantQuery->get()->sortBy('user.name');

        return view('rent_payments.index', compact('payments', 'tenants'));
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
        
        // Generate list of months (Current + 3 months ahead) or Yearly range
        $months = [];
        if ($rental->duration_type === 'yearly') {
            $startYear = \Carbon\Carbon::parse($rental->start_date)->format('Y');
            $endYear = \Carbon\Carbon::parse($rental->end_date)->format('Y');
            $months[] = "Sewa 1 Tahun (" . $startYear . " - " . $endYear . ")";
        } else {
            for ($i = 0; $i < 4; $i++) {
                $months[] = now()->addMonths($i)->format('F Y');
            }
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
        $exists = RentPayment::where('rental_id', $rental->id)
            ->where('month', $request->month)
            ->whereIn('status', ['paid', 'pending'])
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['month' => 'Pembayaran untuk periode ini sudah ada atau sedang menunggu verifikasi.'])->withInput();
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
     * Show invoice for a specific payment (user-facing, with ownership check).
     */
    public function userInvoice(RentPayment $rentPayment)
    {
        $user = auth()->user();
        $tenant = $user->tenant;

        // Ensure payment belongs to this tenant
        if (!$tenant || $rentPayment->tenants_id !== $tenant->id) {
            abort(403);
        }

        $rentPayment->load(['room', 'rental', 'tenants.user']);
        return view('rent_payments.user_invoice', compact('rentPayment'));
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
            if ($activeRental->duration_type === 'yearly') {
                $currentPayment = RentPayment::where('rental_id', $activeRental->id)
                    ->orderByRaw("FIELD(status, 'paid', 'pending', 'unpaid')")
                    ->first();
            } else {
                $currentMonth = date('F Y'); // e.g. "June 2026"
                $currentPayment = RentPayment::where('tenants_id', $tenant->id)
                    ->whereRaw('LOWER(month) = ?', [strtolower($currentMonth)])
                    ->orderByRaw("FIELD(status, 'paid', 'pending', 'unpaid')")
                    ->first();
            }
            
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
        ))->with('midtransClientKey', config('services.midtrans.client_key'));
    }


    /**
     * Get or Generate Midtrans Snap Token
     */
    public function getSnapToken(Request $request)
    {
        $rental = Rental::with(['tenant', 'room'])->findOrFail($request->rental_id);
        $user = auth()->user();

        // Check if there's already an active (unpaid/pending/cancelled) midtrans payment
        $month = date('F Y');
        if ($rental->duration_type === 'yearly') {
            $startYear = \Carbon\Carbon::parse($rental->start_date)->format('Y');
            $endYear = \Carbon\Carbon::parse($rental->end_date)->format('Y');
            $month = "Sewa 1 Tahun (" . $startYear . " - " . $endYear . ")";
        }
        
        $amount = ($rental->duration_type === 'yearly') ? $rental->total_price : ($rental->monthly_price ?? $rental->room->price);

        $paymentQuery = RentPayment::where('rental_id', $rental->id);
        if ($rental->duration_type !== 'yearly') {
            $paymentQuery->where('month', $month);
        }
        
        $payment = $paymentQuery->where('method', 'Midtrans')
            ->whereIn('status', ['unpaid', 'pending'])
            ->first();

        if ($payment) {
            // Update the amount and month description if it changed (e.g. package changed to yearly)
            if ($payment->amount != $amount || $payment->month != $month) {
                $payment->update([
                    'amount' => $amount,
                    'month' => $month,
                    'snap_token' => null, // Reset snap token to force regeneration with new amount
                    'transaction_id' => null
                ]);
            }
        } else {
            $payment = RentPayment::create([
                'rental_id' => $rental->id,
                'room_id' => $rental->room_id,
                'tenants_id' => $rental->tenant_id,
                'month' => $month,
                'amount' => $amount,
                'payment_date' => now(),
                'status' => 'unpaid',
                'method' => 'Midtrans',
            ]);
        }

        // Midtrans Configuration
        Config::$serverKey = trim(config('services.midtrans.server_key'));
        Config::$isProduction = (bool)config('services.midtrans.is_production');
        Config::$isSanitized = (bool)config('services.midtrans.is_sanitized');
        Config::$is3ds = (bool)config('services.midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => 'RENT-' . $payment->id . '-' . time(),
                'gross_amount' => (int)$payment->amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $rental->tenant->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => 'ROOM-' . $rental->room_id,
                    'price' => (int)$payment->amount,
                    'quantity' => 1,
                    'name' => 'Sewa Kamar ' . $rental->room->room_number . ' (' . $month . ')',
                ]
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $payment->update([
                'snap_token' => $snapToken,
                'transaction_id' => $params['transaction_details']['order_id']
            ]);

            return response()->json([
                'snap_token' => $snapToken,
                'payment_id' => $payment->id,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Check real-time payment status from Midtrans
     */
    public function checkPaymentStatus(RentPayment $rentPayment)
    {
        if (!$rentPayment->transaction_id) {
            return response()->json(['message' => 'Belum ada transaksi Midtrans untuk data ini.'], 404);
        }

        Config::$serverKey = trim(config('services.midtrans.server_key'));
        Config::$isProduction = (bool)config('services.midtrans.is_production');

        try {
            $status = Transaction::status($rentPayment->transaction_id);
            $transactionStatus = $status->transaction_status;
            
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                $rentPayment->update(['status' => 'paid']);
                return response()->json(['status' => 'paid', 'message' => 'Pembayaran Berhasil!']);
            } else if ($transactionStatus == 'pending') {
                return response()->json(['status' => 'pending', 'message' => 'Pembayaran masih tertunda.']);
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $rentPayment->update(['status' => 'unpaid']);
                return response()->json(['status' => 'unpaid', 'message' => 'Pembayaran gagal, kadaluarsa, atau dibatalkan.']);
            }

            return response()->json(['status' => $transactionStatus, 'message' => 'Status: ' . $transactionStatus]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengecek status: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Force update payment status (for dummy/demo purposes)
     */
    public function forceUpdateStatus(Request $request, RentPayment $rentPayment)
    {
        $request->validate([
            'status' => 'required|in:paid,pending,unpaid',
        ]);

        $rentPayment->update([
            'status' => $request->status,
            'rejection_reason' => $request->status === 'unpaid' ? ($request->rejection_reason ?? 'Manual status update') : null
        ]);

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui secara manual.');
    }
}
