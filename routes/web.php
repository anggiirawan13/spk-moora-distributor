<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DistributorController;
use App\Http\Controllers\Admin\CriteriaController;
use App\Http\Controllers\Admin\SubCriteriaController;
use App\Http\Controllers\Admin\AlternativeController;
use App\Http\Controllers\Admin\CalculationController;
use App\Http\Controllers\Admin\PaymentTermController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\DeliveryMethodController;
use App\Http\Controllers\Admin\BusinessScaleController;

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
    Route::resource('/distributor', DistributorController::class)->names('distributor');
    Route::get('/distributor/compare/form', [DistributorController::class, 'showComparisonForm'])->name('distributor.compare.form');
    Route::post('/distributor/compare', [DistributorController::class, 'compare'])->name('distributor.compare');

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
        Route::resource('/business_scale', BusinessScaleController::class)->names('business_scale');
        Route::resource('/delivery_method', DeliveryMethodController::class)->names('delivery_method');
        Route::resource('/product_category', ProductCategoryController::class)->names('product_category');
        Route::resource('/payment_term', PaymentTermController::class)->names('payment_term');
        Route::resource('/criteria', CriteriaController::class)->names('criteria');
        Route::resource('/sub-criteria', SubCriteriaController::class)->names('subcriteria');
        Route::resource('/alternative', AlternativeController::class)->names('alternative');
    });
});