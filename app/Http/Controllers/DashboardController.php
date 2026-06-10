<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenants;
use App\Models\Rental;
use App\Models\RentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAnyAdmin()) {
            $isSuperAdmin = $user->isSuperAdmin();
            $district = $user->district; // null for superadmin

            // Scope query: superadmin sees all, regional admin sees only their district
            $roomQuery      = Room::query();
            $rentalQuery    = Rental::query();
            $paymentQuery   = RentPayment::query();
            $tenantQuery    = Tenants::query();

            if (!$isSuperAdmin && $district) {
                $roomQuery->where('district', $district);
                $districtRoomIds = (clone $roomQuery)->pluck('id');
                $rentalQuery->whereIn('room_id', $districtRoomIds);
                $paymentQuery->whereIn('room_id', $districtRoomIds);
                $tenantQuery->whereHas('rentals', function ($q) use ($districtRoomIds) {
                    $q->whereIn('room_id', $districtRoomIds);
                });
            }

            $totalRooms     = (clone $roomQuery)->count();
            $availableRooms = (clone $roomQuery)->where('status', 'available')->count();
            $occupiedRooms  = (clone $roomQuery)->where('status', 'occupied')->count();
            $totalTenants   = (clone $tenantQuery)->count();
            $totalRevenue   = (clone $paymentQuery)->where('status', 'paid')->sum('amount');

            $recentPayments = (clone $paymentQuery)->with(['rental', 'tenants', 'room'])
                ->latest()
                ->take(5)
                ->get();

            $announcementsCount  = \App\Models\Announcement::count();
            $pendingRentalsCount  = (clone $rentalQuery)->where('status', 'pending')->count();
            $pendingPaymentsCount = (clone $paymentQuery)->where('status', 'pending')->count();

            return view('dashboard', compact(
                'totalRooms',
                'availableRooms',
                'occupiedRooms',
                'totalTenants',
                'totalRevenue',
                'recentPayments',
                'announcementsCount',
                'pendingRentalsCount',
                'pendingPaymentsCount',
                'isSuperAdmin'
            ));
        } elseif ($user->isLaundry()) {
            return redirect()->route('laundry.orders.index');
        } elseif ($user->isCleaner()) {
            return redirect()->route('cleaner.orders.index');
        } elseif ($user->isSecurity()) {
            return redirect()->route('security.dashboard');
        } else {
            // User view (Tenant)
            $tenant = $user->tenant;
            
            // Get all rentals (active or pending)
            $myRentals = $tenant ? Rental::where('tenant_id', $tenant->id)
                ->whereIn('status', ['active', 'pending'])
                ->with('room')
                ->get() : collect();

            // Specifically for the "Pay Now" logic, get the first active rental
            $activeRental = $tenant ? Rental::where('tenant_id', $tenant->id)
                ->where('status', 'active')
                ->first() : null;

            $currentPaymentStatus = 'unpaid';
            if ($activeRental) {
                $payment = RentPayment::where('tenants_id', $tenant->id)
                    ->where('month', date('F Y'))
                    ->first();
                
                if ($payment) {
                    $currentPaymentStatus = $payment->status;
                }
            }
            
            $myPayments = $tenant ? RentPayment::where('tenants_id', $tenant->id)
                ->orderBy('payment_date', 'desc')
                ->limit(5)
                ->get() : collect();

            // Fetch Latest Announcements for User/Partner
            $role = $user->role;
            $announcements = \App\Models\Announcement::where('is_active', true)
                ->where(function($query) use ($role) {
                    $query->where('target_role', 'all')
                          ->orWhere('target_role', $role);
                })
                ->where(function($query) {
                    $query->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now());
                })
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
            
            $unreadAnnouncementsCount = $announcements->count(); // Simplification: count active ones as "new"
            
            return view('dashboard_user', compact(
                'tenant', 
                'myRentals', 
                'myPayments', 
                'activeRental', 
                'currentPaymentStatus', 
                'announcements',
                'unreadAnnouncementsCount'
            ));
        }
    }
}
