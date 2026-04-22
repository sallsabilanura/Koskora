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

class CleaningController extends Controller
{
    // --- Admin Actions ---
    public function adminCleaners()
    {
        $cleaners = Cleaner::with('user')->get();
        return view('admin.cleaning.cleaners', compact('cleaners'));
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
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'cleaner',
            ]);

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('cleaners', 'public');
            }

            Cleaner::create([
                'user_id' => $user->id,
                'photo' => $photoPath,
                'bio' => $request->bio,
            ]);
        });

        return redirect()->back()->with('success', 'Petugas kebersihan berhasil didaftarkan.');
    }

    public function adminPackages()
    {
        $packages = CleaningPackage::all();
        return view('admin.cleaning.packages', compact('packages'));
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
        $cleaner = auth()->user()->cleaner;
        $orders = CleaningOrder::where('cleaner_id', $cleaner->id)
            ->with(['user.tenant.rentals.room', 'package'])
            ->latest()
            ->get();
        return view('cleaner.orders', compact('orders'));
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
        $cleaners = Cleaner::with('user')->get();
        $packages = CleaningPackage::all();
        $myOrders = CleaningOrder::where('user_id', auth()->id())
            ->with(['cleaner.user', 'package'])
            ->latest()
            ->get();
            
        return view('user.cleaning.index', compact('cleaners', 'packages', 'myOrders'));
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

        CleaningOrder::create([
            'user_id' => auth()->id(),
            'cleaner_id' => $request->cleaner_id,
            'package_id' => $request->package_id,
            'scheduled_at' => $request->scheduled_at,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'total_price' => $package->price,
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
}
