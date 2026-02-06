<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\SubCriteriaController;
use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\PaymentTermController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DeliveryMethodController;
use App\Http\Controllers\BusinessScaleController;
use App\Http\Controllers\ImportController;

Auth::routes(['register' => false]);

Route::get('/404', function () {
    return response()->view('errors.404', [], 404);
})->name('error.custom.404');

Route::get('/', [HomeController::class, 'index'])->middleware('guest')->name('home');

Route::post('/register', [UserController::class, 'register'])->name('register');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    Route::resource('/distributor', DistributorController::class)->names('distributor');
    Route::get('/distributor/compare/form', [DistributorController::class, 'showComparisonForm'])->name('distributor.compare.form');
    Route::post('/distributor/compare', [DistributorController::class, 'compare'])->name('distributor.compare');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/calculation', [CalculationController::class, 'calculation'])->name('calculation');
    Route::get('/calculation', [CalculationController::class, 'calculation'])->name('moora.calculation');
    Route::get('/moora/report', [CalculationController::class, 'downloadPDF'])->name('moora.download_pdf');

    Route::resource('/user', UserController::class)->names('user');
    Route::resource('/business-scale', BusinessScaleController::class)->names('business_scale');
    Route::resource('/delivery-method', DeliveryMethodController::class)->names('delivery_method');
    Route::resource('/product', ProductController::class)->names('product');
    Route::resource('/payment-term', PaymentTermController::class)->names('payment_term');
    Route::resource('/criteria', CriteriaController::class)->names('criteria');
    Route::resource('/sub-criteria', SubCriteriaController::class)->names('subcriteria');
    Route::resource('/alternative', AlternativeController::class)->names('alternative');

    Route::middleware(['can:admin'])->group(function () {
        Route::get('/import/excel', [ImportController::class, 'index'])->name('import.excel.index');
        Route::post('/import/excel/preview', [ImportController::class, 'preview'])->name('import.excel.preview');
        Route::post('/import/excel', [ImportController::class, 'store'])->name('import.excel.store');
        Route::get('/import/excel/errors/{file}', [ImportController::class, 'downloadErrors'])->name('import.excel.errors');
        Route::get('/import/excel/template', [ImportController::class, 'downloadTemplate'])->name('import.excel.template');
    });
});
