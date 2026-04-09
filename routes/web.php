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
use App\Http\Controllers\ImportApprovalController;

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

    Route::middleware(['can:import-excel'])->group(function () {
        Route::get('/import/excel', [ImportController::class, 'index'])->name('import.excel.index');
        Route::get('/import/excel/history', [ImportController::class, 'history'])->name('import.excel.history');
        Route::post('/import/excel', [ImportController::class, 'store'])->name('import.excel.store');
        Route::get('/import/excel/errors/{file}', [ImportController::class, 'downloadErrors'])->name('import.excel.errors');
        Route::get('/import/excel/template', [ImportController::class, 'downloadTemplate'])->name('import.excel.template');
        Route::get('/import/excel/template-seeder', [ImportController::class, 'downloadSeederTemplate'])->name('import.excel.template_seeder');
    });

    Route::middleware(['can:view-import-approval'])->group(function () {
        Route::get('/import/approvals', [ImportApprovalController::class, 'index'])->name('import.approvals.index');
    });

    Route::middleware(['can:approve-import-admin'])->group(function () {
        Route::post('/import/approvals/{batch}/batch-admin', [ImportApprovalController::class, 'approveBatchAdmin'])->name('import.approvals.batch_admin');
    });

    Route::middleware(['can:approve-import-director'])->group(function () {
        Route::post('/import/approvals/{batch}/batch-director', [ImportApprovalController::class, 'approveBatchDirector'])->name('import.approvals.batch_director');
    });

    Route::middleware(['can:view-import-approval'])->group(function () {
        Route::post('/import/approvals/item/{type}/{id}/approve', [ImportApprovalController::class, 'approveItem'])->name('import.approvals.item_approve');
        Route::post('/import/approvals/item/{type}/{id}/reject', [ImportApprovalController::class, 'rejectItem'])->name('import.approvals.item_reject');
    });
});
