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
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\RoomReviewController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\AdminManagementController;
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
Route::get('/property/{propertyName}', [HomeController::class, 'showProperty'])->name('property.show');
Route::get('/room/{room}', [HomeController::class, 'showRoom'])->name('room.detail');
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
        Route::post('/my-payments/midtrans-token', [RentPaymentController::class, 'getSnapToken'])->name('rent-payments.midtrans-token');
        Route::post('/my-payments/{rentPayment}/check-status', [RentPaymentController::class, 'checkPaymentStatus'])->name('rent-payments.check-status');
        // Laundry Service (User/Tenant)
        Route::get('/laundry', [LaundryController::class, 'userIndex'])->name('user.laundry.index');
        Route::get('/laundry/order/{laundry}', [LaundryController::class, 'userOrder'])->name('user.laundry.order');
        Route::post('/laundry/order/{laundry}', [LaundryController::class, 'userStoreOrder'])->name('user.laundry.store');
        Route::post('/laundry/review', [LaundryController::class, 'userStoreReview'])->name('user.laundry.review.store');
        Route::post('/laundry/midtrans-token', [LaundryController::class, 'getSnapToken'])->name('user.laundry.midtrans-token');
        Route::post('/laundry/order/{order}/check-status', [LaundryController::class, 'checkPaymentStatus'])->name('user.laundry.check-status');
        Route::post('/laundry/order/{order}/payment', [LaundryController::class, 'userSubmitPayment'])->name('user.laundry.payment.store');
        // Cleaning Service (User/Tenant)
        Route::get('/cleaning', [CleaningController::class, 'userIndex'])->name('user.cleaning.index');
        Route::post('/cleaning', [CleaningController::class, 'userStoreOrder'])->name('user.cleaning.store');
        Route::post('/cleaning/midtrans-token', [CleaningController::class, 'getSnapToken'])->name('user.cleaning.midtrans-token');
        Route::post('/cleaning/order/{order}/check-status', [CleaningController::class, 'checkPaymentStatus'])->name('user.cleaning.check-status');
        Route::post('/cleaning/order/{order}/payment', [CleaningController::class, 'userSubmitPayment'])->name('user.cleaning.payment.store');

        // Announcements (User/Tenant)
        Route::get('/announcements', [AnnouncementController::class, 'userIndex'])->name('user.announcements.index');

        // Rental Termination
        Route::post('/rentals/{rental}/terminate', [RentalController::class, 'requestTermination'])->name('rentals.request-termination');

        // Room Reviews
        Route::post('/room-reviews', [RoomReviewController::class, 'store'])->name('room-reviews.store');
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
        Route::get('/laundry/withdrawals', [WithdrawalController::class, 'index'])->name('laundry.withdrawals.index');
        Route::post('/laundry/withdrawals', [WithdrawalController::class, 'store'])->name('laundry.withdrawals.store');
    });

    // Cleaner routes
    Route::middleware('role:cleaner')->group(function () {
        Route::get('/cleaner/orders', [CleaningController::class, 'cleanerOrders'])->name('cleaner.orders.index');
        Route::post('/cleaner/orders/{order}/status', [CleaningController::class, 'cleanerUpdateStatus'])->name('cleaner.orders.status');
        Route::post('/cleaner/orders/{order}/verify-payment', [CleaningController::class, 'cleanerVerifyPayment'])->name('cleaner.verify-payment');
        Route::post('/cleaner/bank-info', [CleaningController::class, 'cleanerUpdateBankInfo'])->name('cleaner.bank-info.update');
        Route::get('/cleaner/withdrawals', [WithdrawalController::class, 'index'])->name('cleaner.withdrawals.index');
        Route::post('/cleaner/withdrawals', [WithdrawalController::class, 'store'])->name('cleaner.withdrawals.store');
    });

    // Security (Satpam) routes
    Route::middleware('role:security')->group(function () {
        Route::get('/security/dashboard', [SecurityController::class, 'dashboard'])->name('security.dashboard');
        Route::get('/security/attendance', [SecurityController::class, 'attendance'])->name('security.attendance');
        Route::post('/security/attendance', [SecurityController::class, 'storeAttendance'])->name('security.attendance.store');
        Route::get('/security/report', [SecurityController::class, 'report'])->name('security.report');
        Route::post('/security/report', [SecurityController::class, 'storeReport'])->name('security.report.store');
        Route::get('/security/shifts', [SecurityController::class, 'shifts'])->name('security.shifts');
    });

    // Admin & Super Admin shared routes
    Route::middleware('role:admin,superadmin')->group(function () {
        Route::resource('rooms', RoomController::class);

        Route::post('/rooms/{room}/image/delete', [RoomController::class, 'destroyImage'])->name('rooms.image.destroy');
        Route::resource('tenants', TenantsController::class);
        Route::resource('rentals', RentalController::class);
        Route::post('/rentals/{rental}/approve', [RentalController::class, 'approve'])->name('rentals.approve');
        Route::resource('rent-payments', RentPaymentController::class);
        Route::post('/rent-payments/{rentPayment}/verify', [RentPaymentController::class, 'verify'])->name('rent-payments.verify');
        Route::post('/rent-payments/{rentPayment}/reject', [RentPaymentController::class, 'reject'])->name('rent-payments.reject');
        Route::post('/rent-payments/{rentPayment}/force-status', [RentPaymentController::class, 'forceUpdateStatus'])->name('rent-payments.force-status');
        
        // Laundry Management
        Route::get('/admin/laundries', [LaundryController::class, 'adminIndex'])->name('admin.laundries.index');
        Route::post('/admin/laundries', [LaundryController::class, 'adminStore'])->name('admin.laundries.store');
        Route::get('/admin/laundries/{laundry}/edit', [LaundryController::class, 'adminEdit'])->name('admin.laundries.edit');
        Route::put('/admin/laundries/{laundry}', [LaundryController::class, 'adminUpdate'])->name('admin.laundries.update');

        // Cleaning Management
        Route::get('/admin/cleaning', [CleaningController::class, 'adminIndex'])->name('admin.cleaning.index');
        Route::post('/admin/cleaning/cleaners', [CleaningController::class, 'adminCleanerStore'])->name('admin.cleaning.cleaners.store');
        Route::post('/admin/cleaning/packages', [CleaningController::class, 'adminPackageStore'])->name('admin.cleaning.packages.store');

        // Announcement Management
        Route::resource('admin/announcements', AnnouncementController::class, ['names' => 'admin.announcements']);

        // Security Management
        Route::get('/admin/security', [SecurityController::class, 'adminIndex'])->name('admin.security.index');
        Route::post('/admin/security/staff', [SecurityController::class, 'adminStoreStaff'])->name('admin.security.staff.store');
        Route::post('/admin/security/shift', [SecurityController::class, 'adminStoreShift'])->name('admin.security.shift.store');

        // Withdrawals Management
        Route::get('/admin/withdrawals', [WithdrawalController::class, 'adminIndex'])->name('admin.withdrawals.index');
        Route::post('/admin/withdrawals/{withdrawal}/approve', [WithdrawalController::class, 'adminApprove'])->name('admin.withdrawals.approve');
        Route::post('/admin/withdrawals/{withdrawal}/reject', [WithdrawalController::class, 'adminReject'])->name('admin.withdrawals.reject');
    });

    // Super Admin exclusive routes (Kelola Admin Daerah)
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/superadmin/admins', [AdminManagementController::class, 'index'])->name('superadmin.admins.index');
        Route::post('/superadmin/admins', [AdminManagementController::class, 'store'])->name('superadmin.admins.store');
        Route::delete('/superadmin/admins/{user}', [AdminManagementController::class, 'destroy'])->name('superadmin.admins.destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Midtrans Notification - Webhook
Route::post('/midtrans/notification', [MidtransController::class, 'notificationHandler']);
