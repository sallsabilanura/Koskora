<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PendingApprovalController extends Controller
{
    /**
     * Show the "Waiting for Approval" page for a pending user.
     */
    public function index()
    {
        $user = auth()->user();

        // If user is already active, redirect to dashboard
        if ($user->isActive() || $user->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        // If rejected, log out and redirect to login
        if ($user->isRejected()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda telah ditolak oleh administrator.');
        }

        return view('pending-approval');
    }

    /**
     * Show all pending registrations for superadmin.
     */
    public function adminIndex(Request $request)
    {
        $query = User::whereIn('status', ['pending', 'rejected'])
            ->whereIn('role', ['user', 'admin']);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $filter = $request->get('filter', 'pending');
        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $pendingUsers = $query->latest()->get();

        $counts = [
            'pending'  => User::whereIn('role', ['user', 'admin'])->where('status', 'pending')->count(),
            'rejected' => User::whereIn('role', ['user', 'admin'])->where('status', 'rejected')->count(),
        ];

        return view('admin.approvals.index', compact('pendingUsers', 'counts', 'filter'));
    }

    /**
     * Approve a pending user account.
     */
    public function approve(User $user)
    {
        \Log::info('PendingApprovalController::approve called for user: ' . $user->id);
        if (!in_array($user->status, ['pending', 'rejected'])) {
            \Log::info('User status not pending/rejected: ' . $user->status);
            return back()->with('error', 'Akun ini tidak dalam status pending.');
        }

        $user->update(['status' => 'active']);
        \Log::info('User approved successfully: ' . $user->id);

        return back()->with('success', "Akun {$user->name} ({$user->role}) berhasil disetujui.");
    }

    /**
     * Reject a pending user account.
     */
    public function reject(Request $request, User $user)
    {
        \Log::info('PendingApprovalController::reject called for user: ' . $user->id);
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $user->update(['status' => 'rejected']);
        \Log::info('User rejected successfully: ' . $user->id);

        return back()->with('success', "Akun {$user->name} berhasil ditolak.");
    }
}
