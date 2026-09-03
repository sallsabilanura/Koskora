<?php

namespace App\Http\Controllers;

use App\Models\Cleaner;
use App\Models\CleaningPackage;
use App\Models\CleaningOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class CleaningController extends Controller
{
    // --- Admin Actions ---
    public function adminIndex()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();

        $cleanersQuery  = Cleaner::with('user');
        $packagesQuery  = CleaningPackage::query();
        $ordersQuery    = CleaningOrder::with(['user.tenant.rentals.room', 'package', 'cleaner.user']);

        if (!$isSuperAdmin) {
            $safeDistrict = $user->district ?? 'NOT_SET';
            $cleanersQuery->whereHas('user', function ($q) use ($safeDistrict) {
                $q->where('district', $safeDistrict);
            });
            $cleanerIds = (clone $cleanersQuery)->pluck('id');
            $ordersQuery->whereIn('cleaner_id', $cleanerIds);
        }

        $cleaners = $cleanersQuery->get();
        $packages = $packagesQuery->get();
        $orders   = $ordersQuery->latest()->get();

        return view('admin.cleaning.index', compact('cleaners', 'packages', 'orders'));
    }

    public function adminCleanerCreate()
    {
        return view('admin.cleaning.create_cleaner');
    }

    public function adminCleanerStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'photo' => 'nullable|image|max:2048',
            'bio' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $admin = auth()->user();
            $user = User::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'password'          => Hash::make($request->password),
                'role'              => 'cleaner',
                'district'          => $admin->district, // Inherit district from registering admin
                'email_verified_at' => now(),
            ]);

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('cleaners', 'public');
            }

            Cleaner::create([
                'user_id' => $user->id,
                'photo'   => $photoPath,
                'bio'     => $request->bio,
            ]);
        });

        return redirect()->back()->with('success', 'Petugas kebersihan berhasil didaftarkan.');
    }

    public function adminPackages()
    {
        $packages = CleaningPackage::all();
        return view('admin.cleaning.packages', compact('packages'));
    }

    public function adminPackageCreate()
    {
        return view('admin.cleaning.create_package');
    }

    public function adminPackageStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        CleaningPackage::create($request->all());
        return redirect()->back()->with('success', 'Paket bebersih berhasil ditambahkan.');
    }

    // --- Cleaner Actions ---
    public function cleanerOrders()
    {
        $user = auth()->user();
        $cleaner = $user->cleaner;
        $orders = CleaningOrder::where('cleaner_id', $cleaner->id)
            ->with(['user.tenant.rentals.room', 'package'])
            ->latest()
            ->get();
            
        $totalEarned = CleaningOrder::where('cleaner_id', $cleaner->id)
            ->where('payment_status', 'paid')
            ->sum('partner_amount');
            
        $totalWithdrawn = \App\Models\Withdrawal::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');
            
        $balance = $totalEarned - $totalWithdrawn;
            
        return view('cleaner.orders', compact('orders', 'balance'));
    }

    public function cleanerUpdateStatus(Request $request, CleaningOrder $order)
    {
        $request->validate(['status' => 'required|in:pending,approved,working,done,cancelled']);
        
        if ($order->cleaner_id !== auth()->user()->cleaner->id) {
            abort(403);
        }

        $order->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status pesanan diperbarui.');
    }

    public function cleanerVerifyPayment(CleaningOrder $order)
    {
        if ($order->cleaner_id !== auth()->user()->cleaner->id) {
            abort(403);
        }

        $order->update(['payment_status' => 'paid']);
        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function cleanerUpdateBankInfo(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
        ]);

        $cleaner = auth()->user()->cleaner;
        $cleaner->update([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
        ]);

        return redirect()->back()->with('success', 'Informasi rekening berhasil diperbarui.');
    }

    // --- User/Tenant Actions ---
    public function userIndex()
    {
        $user = auth()->user();
        $tenant = $user->tenant;

        // Filter cleaners by tenant's room district
        $cleanersQuery = Cleaner::with('user');
        if ($tenant) {
            $activeRental = \App\Models\Rental::where('tenant_id', $tenant->id)
                ->whereIn('status', ['active'])
                ->with('room')
                ->first();
            if ($activeRental && $activeRental->room && $activeRental->room->district) {
                $tenantDistrict = $activeRental->room->district;
                $cleanersQuery->whereHas('user', function ($q) use ($tenantDistrict) {
                    $q->where('district', $tenantDistrict);
                });
            }
        }

        $cleaners = $cleanersQuery->get();
        $packages = CleaningPackage::all();
        $myOrders = CleaningOrder::where('user_id', auth()->id())
            ->with(['cleaner.user', 'package'])
            ->latest()
            ->get();

        return view('user.cleaning.index', compact('cleaners', 'packages', 'myOrders'))
            ->with('midtransClientKey', config('services.midtrans.client_key'));
    }

    public function userStoreOrder(Request $request)
    {
        $request->validate([
            'cleaner_id' => 'required|exists:cleaners,id',
            'package_id' => 'required|exists:cleaning_packages,id',
            'scheduled_at' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        $package = CleaningPackage::findOrFail($request->package_id);

        $commissionAmount = $package->price * 0.1;
        $partnerAmount = $package->price - $commissionAmount;

        CleaningOrder::create([
            'user_id' => auth()->id(),
            'cleaner_id' => $request->cleaner_id,
            'package_id' => $request->package_id,
            'scheduled_at' => $request->scheduled_at,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'total_price' => $package->price,
            'commission_amount' => $commissionAmount,
            'partner_amount' => $partnerAmount,
            'notes' => $request->notes,
        ]);

        return redirect()->route('user.cleaning.index')->with('success', 'Pesanan bebersih berhasil dikirim! Petugas akan datang sesuai jadwal.');
    }

    public function userSubmitPayment(Request $request, CleaningOrder $order)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $path = $request->file('payment_proof')->store('cleaning_payments', 'public');
        
        $order->update([
            'payment_proof' => $path,
            'payment_status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi petugas.');
    }

    public function getSnapToken(Request $request)
    {
        $order = CleaningOrder::with('package')->findOrFail($request->order_id);
        $user = auth()->user();

        // Midtrans Configuration
        Config::$serverKey = trim(config('services.midtrans.server_key'));
        Config::$isProduction = (bool)config('services.midtrans.is_production');
        Config::$isSanitized = (bool)config('services.midtrans.is_sanitized');
        Config::$is3ds = (bool)config('services.midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => 'CLEANING-' . $order->id . '-' . time(),
                'gross_amount' => (int)$order->total_price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => 'CLN-' . $order->package_id,
                    'price' => (int)$order->total_price,
                    'quantity' => 1,
                    'name' => 'Layanan Kebersihan: ' . $order->package->name,
                ]
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $order->update([
                'snap_token' => $snapToken,
                'transaction_id' => $params['transaction_details']['order_id']
            ]);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function checkPaymentStatus(CleaningOrder $order)
    {
        if (!$order->transaction_id) {
            return response()->json(['message' => 'Belum ada transaksi Midtrans untuk pesanan ini.'], 404);
        }

        Config::$serverKey = trim(config('services.midtrans.server_key'));
        Config::$isProduction = (bool)config('services.midtrans.is_production');

        try {
            $status = Transaction::status($order->transaction_id);
            $transactionStatus = $status->transaction_status;
            
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                $order->update(['payment_status' => 'paid']);
                return response()->json(['status' => 'paid', 'message' => 'Pembayaran Berhasil!']);
            } else if ($transactionStatus == 'pending') {
                return response()->json(['status' => 'pending', 'message' => 'Pembayaran masih tertunda.']);
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $order->update(['payment_status' => 'unpaid']);
                return response()->json(['status' => 'unpaid', 'message' => 'Pembayaran gagal, kadaluarsa, atau dibatalkan.']);
            }

            return response()->json(['status' => $transactionStatus, 'message' => 'Status: ' . $transactionStatus]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengecek status: ' . $e->getMessage()], 500);
        }
    }
}
