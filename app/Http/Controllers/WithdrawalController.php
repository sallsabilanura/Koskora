<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Models\LaundryOrder;
use App\Models\CleaningOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WithdrawalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $balance = $this->calculateBalance($user);
        $withdrawals = Withdrawal::where('user_id', $user->id)->latest()->get();

        if ($user->isLaundry()) {
            return view('laundry.withdrawals', compact('balance', 'withdrawals'));
        } elseif ($user->isCleaner()) {
            return view('cleaner.withdrawals', compact('balance', 'withdrawals'));
        }

        abort(403);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $balance = $this->calculateBalance($user);

        $request->validate([
            'amount' => 'required|numeric|min:10000|max:' . $balance,
            'notes' => 'nullable|string',
        ]);

        Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Permintaan pencairan dana berhasil dikirim.');
    }

    private function calculateBalance($user)
    {
        $totalEarned = 0;
        
        if ($user->isLaundry()) {
            $laundry = $user->laundry;
            $totalEarned = LaundryOrder::where('laundry_id', $laundry->id)
                ->where('payment_status', 'paid')
                ->sum('partner_amount');
        } elseif ($user->isCleaner()) {
            $cleaner = $user->cleaner;
            $totalEarned = CleaningOrder::where('cleaner_id', $cleaner->id)
                ->where('payment_status', 'paid')
                ->sum('partner_amount');
        }

        $totalWithdrawn = Withdrawal::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');

        return $totalEarned - $totalWithdrawn;
    }

    // --- Admin Actions ---
    public function adminIndex()
    {
        $user = auth()->user();
        $adminDistrict = $user->isSuperAdmin() ? null : $user->district;

        $query = Withdrawal::with('user')->latest();

        // Filter withdrawals by partner's district
        if ($adminDistrict) {
            $query->whereHas('user', function ($q) use ($adminDistrict) {
                $q->where('district', $adminDistrict);
            });
        }

        $withdrawals = $query->get();
        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function adminApprove(Request $request, Withdrawal $withdrawal)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        $path = $request->file('payment_proof')->store('withdrawal_proofs', 'public');

        $withdrawal->update([
            'status' => 'approved',
            'payment_proof' => $path,
        ]);

        return redirect()->back()->with('success', 'Permintaan pencairan disetujui dan bukti pembayaran telah diunggah.');
    }

    public function adminReject(Request $request, Withdrawal $withdrawal)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);

        $withdrawal->update([
            'status' => 'rejected',
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Permintaan pencairan ditolak.');
    }
}
