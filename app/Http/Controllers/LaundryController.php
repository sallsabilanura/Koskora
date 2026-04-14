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

class LaundryController extends Controller
{
    // --- Admin Actions ---
    public function adminIndex()
    {
        $laundries = Laundry::with('user')->get();
        return view('admin.laundries.index', compact('laundries'));
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
            $user = User::create([
                'name' => $request->partner_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'laundry',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('laundries', 'public');
            }

            Laundry::create([
                'user_id' => $user->id,
                'name' => $request->laundry_name,
                'address' => $request->address,
                'phone' => $request->phone,
                'image' => $imagePath,
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
        $laundry = auth()->user()->laundry;
        $orders = LaundryOrder::where('laundry_id', $laundry->id)
            ->with('user.tenant.rentals.room')
            ->latest()
            ->get();
        return view('laundry.orders', compact('orders'));
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
        $laundries = Laundry::withCount('reviews')->get();
        
        // Transform laundries to include average rating
        $laundries->map(function ($laundry) {
            $laundry->avg_rating = $laundry->averageRating();
            return $laundry;
        });

        $myOrders = LaundryOrder::where('user_id', auth()->id())
            ->with(['laundry', 'review'])
            ->latest()
            ->get();
            
        return view('user.laundry.index', compact('laundries', 'myOrders'));
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

        LaundryOrder::create([
            'user_id' => auth()->id(),
            'laundry_id' => $laundry->id,
            'items' => $finalItems,
            'total_price' => $totalPrice,
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
}
