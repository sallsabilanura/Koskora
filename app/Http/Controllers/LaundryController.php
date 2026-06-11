<?php

namespace App\Http\Controllers;

use App\Models\Laundry;
use App\Models\LaundryService;
use App\Models\LaundryOrder;
use App\Models\LaundryReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class LaundryController extends Controller
{
    // --- Admin Actions ---
    public function adminIndex()
    {
        $user = auth()->user();
        $adminDistrict = $user->isSuperAdmin() ? null : $user->district;

        $laundriesQuery = Laundry::with('user');
        $ordersQuery    = LaundryOrder::with(['user.tenant.rentals.room', 'laundry']);
        $servicesQuery  = LaundryService::with('laundry');

        if ($adminDistrict) {
            $laundriesQuery->whereHas('user', function ($q) use ($adminDistrict) {
                $q->where('district', $adminDistrict);
            });
            $laundriesIds = (clone $laundriesQuery)->pluck('id');
            $ordersQuery->whereIn('laundry_id', $laundriesIds);
            $servicesQuery->whereIn('laundry_id', $laundriesIds);
        }

        $laundries = $laundriesQuery->get();
        $orders    = $ordersQuery->latest()->get();
        $services  = $servicesQuery->get();

        return view('admin.laundries.index', compact('laundries', 'orders', 'services'));
    }

    public function adminCreate()
    {
        return view('admin.laundries.create');
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'partner_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'laundry_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $admin = auth()->user();
            $user = User::create([
                'name'              => $request->partner_name,
                'email'             => $request->email,
                'password'          => Hash::make($request->password),
                'role'              => 'laundry',
                'district'          => $admin->district, // Inherit district from registering admin
                'email_verified_at' => now(),
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('laundries', 'public');
            }

            Laundry::create([
                'user_id' => $user->id,
                'name'    => $request->laundry_name,
                'address' => $request->address,
                'phone'   => $request->phone,
                'image'   => $imagePath,
            ]);
        });

        return redirect()->back()->with('success', 'Partner Laundry berhasil didaftarkan.');
    }

    public function adminEdit(Laundry $laundry)
    {
        $laundry->load('user');
        return view('admin.laundries.edit', compact('laundry'));
    }

    public function adminUpdate(Request $request, Laundry $laundry)
    {
        $request->validate([
            'partner_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $laundry->user_id,
            'laundry_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request, $laundry) {
            // Update User
            $laundry->user->update([
                'name' => $request->partner_name,
                'email' => $request->email,
            ]);

            // Update Laundry
            $data = [
                'name' => $request->laundry_name,
                'address' => $request->address,
                'phone' => $request->phone,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'account_name' => $request->account_name,
            ];

            if ($request->hasFile('image')) {
                // Store new image
                $data['image'] = $request->file('image')->store('laundries', 'public');
            }

            $laundry->update($data);
        });

        return redirect()->route('admin.laundries.index')->with('success', 'Data Partner Laundry berhasil diperbarui.');
    }

    // --- Laundry Partner Actions ---
    public function partnerServices()
    {
        $laundry = auth()->user()->laundry;
        $services = $laundry->services;
        return view('laundry.services', compact('laundry', 'services'));
    }

    public function partnerServiceStore(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $laundry = auth()->user()->laundry;
        LaundryService::create([
            'laundry_id' => $laundry->id,
            'item_name' => $request->item_name,
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function partnerServiceDestroy(LaundryService $service)
    {
        if ($service->laundry_id !== auth()->user()->laundry->id) {
            abort(403);
        }
        $service->delete();
        return redirect()->back()->with('success', 'Layanan berhasil dihapus.');
    }

    public function partnerUpdateBankInfo(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
        ]);

        $laundry = auth()->user()->laundry;
        $laundry->update([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
        ]);

        return redirect()->back()->with('success', 'Informasi rekening berhasil diperbarui.');
    }

    public function partnerOrders()
    {
        $user = auth()->user();
        $laundry = $user->laundry;
        $orders = LaundryOrder::where('laundry_id', $laundry->id)
            ->with('user.tenant.rentals.room')
            ->latest()
            ->get();
            
        $totalEarned = LaundryOrder::where('laundry_id', $laundry->id)
            ->where('payment_status', 'paid')
            ->sum('partner_amount');
            
        $totalWithdrawn = \App\Models\Withdrawal::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');
            
        $balance = $totalEarned - $totalWithdrawn;
            
        return view('laundry.orders', compact('orders', 'balance'));
    }

    public function partnerUpdateStatus(Request $request, LaundryOrder $order)
    {
        $request->validate(['status' => 'required|in:pending,picked_up,in_progress,ready,delivered,done']);
        
        if ($order->laundry_id !== auth()->user()->laundry->id) {
            abort(403);
        }

        $order->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status pesanan diperbarui.');
    }

    public function partnerVerifyPayment(LaundryOrder $order)
    {
        if ($order->laundry_id !== auth()->user()->laundry->id) {
            abort(403);
        }

        $order->update(['payment_status' => 'paid']);
        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    // --- User/Tenant Actions ---
    public function userIndex()
    {
        $user = auth()->user();
        $tenant = $user->tenant;

        // Filter laundries by the tenant's room district
        $laundriesQuery = Laundry::withCount('reviews');
        if ($tenant) {
            $activeRental = \App\Models\Rental::where('tenant_id', $tenant->id)
                ->whereIn('status', ['active'])
                ->with('room')
                ->first();
            if ($activeRental && $activeRental->room && $activeRental->room->district) {
                $tenantDistrict = $activeRental->room->district;
                $laundriesQuery->whereHas('user', function ($q) use ($tenantDistrict) {
                    $q->where('district', $tenantDistrict);
                });
            }
        }

        $laundries = $laundriesQuery->get();

        // Transform laundries to include average rating
        $laundries->map(function ($laundry) {
            $laundry->avg_rating = $laundry->averageRating();
            return $laundry;
        });

        $myOrders = LaundryOrder::where('user_id', auth()->id())
            ->with(['laundry', 'review'])
            ->latest()
            ->get();

        return view('user.laundry.index', compact('laundries', 'myOrders'))
            ->with('midtransClientKey', config('services.midtrans.client_key'));
    }

    public function userOrder(Laundry $laundry)
    {
        $laundry->load('services');
        return view('user.laundry.order', compact('laundry'));
    }

    public function userStoreOrder(Request $request, Laundry $laundry)
    {
        // Simple validation: must have at least one item with qty > 0
        $itemsData = $request->items; // Expecting [service_id => qty]
        if (!$itemsData || count(array_filter($itemsData)) == 0) {
            return redirect()->back()->with('error', 'Pilih minimal satu jenis pakaian.');
        }

        $services = LaundryService::whereIn('id', array_keys($itemsData))->get();
        $finalItems = [];
        $totalPrice = 0;

        foreach ($services as $service) {
            $qty = (int)$itemsData[$service->id];
            if ($qty > 0) {
                $finalItems[] = [
                    'item' => $service->item_name,
                    'qty' => $qty,
                    'price' => (float)$service->price,
                    'subtotal' => $qty * $service->price
                ];
                $totalPrice += ($qty * $service->price);
            }
        }

        $commissionAmount = $totalPrice * 0.1;
        $partnerAmount = $totalPrice - $commissionAmount;

        LaundryOrder::create([
            'user_id' => auth()->id(),
            'laundry_id' => $laundry->id,
            'items' => $finalItems,
            'total_price' => $totalPrice,
            'commission_amount' => $commissionAmount,
            'partner_amount' => $partnerAmount,
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        return redirect()->route('user.laundry.index')->with('success', 'Pesanan laundry berhasil dikirim! Partner akan segera menjemput ke kamar Anda.');
    }

    public function userStoreReview(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:laundry_orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $order = LaundryOrder::findOrFail($request->order_id);

        // Check ownership & status
        if ($order->user_id !== auth()->id() || $order->status !== 'done') {
            abort(403);
        }

        // Prevent double review
        if ($order->review) {
            return redirect()->back()->with('error', 'Pesanan ini sudah diberi rating.');
        }

        LaundryReview::create([
            'laundry_id' => $order->laundry_id,
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas penilaian Anda!');
    }

    public function userSubmitPayment(Request $request, LaundryOrder $order)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $path = $request->file('payment_proof')->store('laundry_payments', 'public');
        
        $order->update([
            'payment_proof' => $path,
            'payment_status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi partner.');
    }

    public function getSnapToken(Request $request)
    {
        $order = LaundryOrder::findOrFail($request->order_id);
        $user = auth()->user();

        // Midtrans Configuration
        Config::$serverKey = trim(config('services.midtrans.server_key'));
        Config::$isProduction = (bool)config('services.midtrans.is_production');
        Config::$isSanitized = (bool)config('services.midtrans.is_sanitized');
        Config::$is3ds = (bool)config('services.midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => 'LAUNDRY-' . $order->id . '-' . time(),
                'gross_amount' => (int)$order->total_price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => array_map(function($item) {
                return [
                    'id' => 'LND-' . str_replace(' ', '_', $item['item']),
                    'price' => (int)$item['price'],
                    'quantity' => (int)$item['qty'],
                    'name' => $item['item'],
                ];
            }, $order->items),
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

    public function checkPaymentStatus(LaundryOrder $order)
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
