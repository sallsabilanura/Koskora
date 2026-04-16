<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TenantsController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\RentPaymentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LaundryController;
use App\Http\Controllers\CleaningController;
use App\Http\Controllers\AnnouncementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Profile Completion for First-time Renters
    Route::get('/tenant/complete-profile', [BookingController::class, 'completeProfile'])->name('bookings.complete-profile');
    Route::post('/tenant/complete-profile', [BookingController::class, 'storeProfile'])->name('bookings.store-profile');
    
    // User/Tenant routes
    Route::middleware('role:user')->group(function () {
        // Booking Flow
        Route::post('/bookings/{room}/rent', [BookingController::class, 'rent'])->name('bookings.rent');
        Route::get('/bookings/{room}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::post('/bookings/{room}/store', [BookingController::class, 'store'])->name('bookings.store');
        
        // Payments
        Route::get('/my-payments', [RentPaymentController::class, 'myPayments'])->name('rent-payments.my-payments');
        Route::get('/my-payments/create', [RentPaymentController::class, 'userCreate'])->name('rent-payments.user-create');
        Route::post('/my-payments/store', [RentPaymentController::class, 'userStore'])->name('rent-payments.user-store');
        Route::get('/my-payments/{rentPayment}/ticket', [RentPaymentController::class, 'showTicket'])->name('rent-payments.ticket');
        // Laundry Service (User/Tenant)
        Route::get('/laundry', [LaundryController::class, 'userIndex'])->name('user.laundry.index');
        Route::get('/laundry/order/{laundry}', [LaundryController::class, 'userOrder'])->name('user.laundry.order');
        Route::post('/laundry/order/{laundry}', [LaundryController::class, 'userStoreOrder'])->name('user.laundry.store');
        Route::post('/laundry/review', [LaundryController::class, 'userStoreReview'])->name('user.laundry.review.store');
        Route::post('/laundry/order/{order}/payment', [LaundryController::class, 'userSubmitPayment'])->name('user.laundry.payment.store');
        // Cleaning Service (User/Tenant)
        Route::get('/cleaning', [CleaningController::class, 'userIndex'])->name('user.cleaning.index');
        Route::post('/cleaning', [CleaningController::class, 'userStoreOrder'])->name('user.cleaning.store');

        // Announcements (User/Tenant)
        Route::get('/announcements', [AnnouncementController::class, 'userIndex'])->name('user.announcements.index');
    });

    // Laundry Partner routes
    Route::middleware('role:laundry')->group(function () {
        // Services/Prices
        Route::get('/laundry/services', [LaundryController::class, 'partnerServices'])->name('laundry.services.index');
        Route::post('/laundry/services', [LaundryController::class, 'partnerServiceStore'])->name('laundry.services.store');
        Route::delete('/laundry/services/{service}', [LaundryController::class, 'partnerServiceDestroy'])->name('laundry.services.destroy');

        // Orders
        Route::get('/laundry/orders', [LaundryController::class, 'partnerOrders'])->name('laundry.orders.index');
        Route::post('/laundry/orders/{order}/status', [LaundryController::class, 'partnerUpdateStatus'])->name('laundry.orders.status');
        Route::post('/laundry/orders/{order}/verify-payment', [LaundryController::class, 'partnerVerifyPayment'])->name('laundry.orders.verify-payment');
        Route::post('/laundry/bank-info', [LaundryController::class, 'partnerUpdateBankInfo'])->name('laundry.bank-info.update');
    });

    // Cleaner routes
    Route::middleware('role:cleaner')->group(function () {
        Route::get('/cleaner/orders', [CleaningController::class, 'cleanerOrders'])->name('cleaner.orders.index');
        Route::post('/cleaner/orders/{order}/status', [CleaningController::class, 'cleanerUpdateStatus'])->name('cleaner.orders.status');
    });

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('rooms', RoomController::class);

        Route::post('/rooms/{room}/image/delete', [RoomController::class, 'destroyImage'])->name('rooms.image.destroy');
        Route::resource('tenants', TenantsController::class);
        Route::resource('rentals', RentalController::class);
        Route::post('/rentals/{rental}/approve', [RentalController::class, 'approve'])->name('rentals.approve');
        Route::resource('rent-payments', RentPaymentController::class);
        Route::post('/rent-payments/{rentPayment}/verify', [RentPaymentController::class, 'verify'])->name('rent-payments.verify');
        Route::post('/rent-payments/{rentPayment}/reject', [RentPaymentController::class, 'reject'])->name('rent-payments.reject');
        
        // Laundry Management
        Route::get('/admin/laundries', [LaundryController::class, 'adminIndex'])->name('admin.laundries.index');
        Route::post('/admin/laundries', [LaundryController::class, 'adminStore'])->name('admin.laundries.store');
        Route::get('/admin/laundries/{laundry}/edit', [LaundryController::class, 'adminEdit'])->name('admin.laundries.edit');
        Route::put('/admin/laundries/{laundry}', [LaundryController::class, 'adminUpdate'])->name('admin.laundries.update');

        // Cleaning Management
        Route::get('/admin/cleaning/cleaners', [CleaningController::class, 'adminCleaners'])->name('admin.cleaning.cleaners');
        Route::post('/admin/cleaning/cleaners', [CleaningController::class, 'adminCleanerStore'])->name('admin.cleaning.cleaners.store');
        Route::get('/admin/cleaning/packages', [CleaningController::class, 'adminPackages'])->name('admin.cleaning.packages');
        Route::post('/admin/cleaning/packages', [CleaningController::class, 'adminPackageStore'])->name('admin.cleaning.packages.store');

        // Announcement Management
        Route::resource('admin/announcements', AnnouncementController::class, ['names' => 'admin.announcements']);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
