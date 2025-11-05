<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\CriteriaController;
use App\Http\Controllers\Admin\SubCriteriaController;
use App\Http\Controllers\Admin\AlternativeController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CalculationController;
use App\Http\Controllers\Admin\CarBrandController;
use App\Http\Controllers\Admin\CarTypeController;
use App\Http\Controllers\Admin\FuelTypeController;
use App\Http\Controllers\Admin\TransmissionTypeController;

Auth::routes();

// Custom 404 route
Route::get('/404', function () {
    return response()->view('admin.errors.404', [], 404);
})->name('error.custom.404');

// Guest only homepage
Route::get('/', [HomeController::class, 'index'])->middleware('guest')->name('home');

// Override registration route to use custom controller
Route::post('/register', [UserController::class, 'register'])->name('register');

// Authenticated users
Route::middleware(['auth'])->group(function () {
    // Profile management
    Route::get('/profile', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/delete-image', [UserController::class, 'deleteProfileImage'])->name('profile.delete_image');

    // ===================================
    // Shared Resources
    // ===================================
    Route::resource('/car', CarController::class)->names('car');
    Route::get('/car/compare/form', [CarController::class, 'showComparisonForm'])->name('car.compare.form');
    Route::post('/car/compare', [CarController::class, 'compare'])->name('car.compare');
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

    // ==============================
    // USER-ONLY FEATURES (not admin)
    // ==============================
    Route::middleware(['not_admin'])->group(function () {
        Route::get('/calculation', [CalculationController::class, 'calculationUser'])->name('calculation.user');
        Route::get('/moora/report', [CalculationController::class, 'downloadPDFUser'])->name('moora.download_pdf_user');
    });

    // ============================
    // ADMIN-ONLY FEATURES (admin)
    // ============================
    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/calculation', [CalculationController::class, 'calculation'])->name('calculation');
        Route::get('/moora/report', [CalculationController::class, 'downloadPDF'])->name('moora.download_pdf');

        Route::resource('/user', UserController::class)->names('user');
        Route::resource('/transmission', TransmissionTypeController::class)->names('transmission_type');
        Route::resource('/fuel', FuelTypeController::class)->names('fuel_type');
        Route::resource('/type', CarTypeController::class)->names('car_type');
        Route::resource('/brand', CarBrandController::class)->names('car_brand');
        Route::resource('/criteria', CriteriaController::class)->names('criteria');
        Route::resource('/sub-criteria', SubCriteriaController::class)->names('subcriteria');
        Route::resource('/alternative', AlternativeController::class)->names('alternative');
        Route::patch('/booking/{id}/status', [BookingController::class, 'updateStatus'])->name('booking.updateStatus');
    });
});
