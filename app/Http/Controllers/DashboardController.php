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

        if ($user->isAdmin()) {
            $totalRooms = Room::count();
            $availableRooms = Room::where('status', 'available')->count();
            $occupiedRooms = Room::where('status', 'occupied')->count();
            $totalTenants = Tenants::count();
            $totalRevenue = RentPayment::where('status', 'paid')->sum('amount');
            
            $recentPayments = RentPayment::with(['rental', 'tenants', 'room'])
                ->orderBy('payment_date', 'desc')
                ->limit(5)
                ->get();
 
            return view('dashboard', compact(
                'totalRooms',
                'availableRooms',
                'occupiedRooms',
                'totalTenants',
                'totalRevenue',
                'recentPayments'
            ));
        } elseif ($user->isLaundry()) {
            return redirect()->route('laundry.orders.index');
        } elseif ($user->isCleaner()) {
            return redirect()->route('cleaner.orders.index');
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
            
            return view('dashboard_user', compact('tenant', 'myRentals', 'myPayments', 'activeRental', 'currentPaymentStatus'));
        }
    }
}
