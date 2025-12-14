<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\Web\DashboardController::class, 'index'])->name('dashboard');

Route::resource('products', \App\Http\Controllers\Web\ProductController::class);
Route::resource('products.bom', \App\Http\Controllers\Web\ProductBomController::class)->except(['show']);
Route::resource('suppliers', \App\Http\Controllers\Web\SupplierController::class);
Route::resource('purchase-orders', \App\Http\Controllers\Web\PurchaseOrderController::class);
Route::resource('purchase-orders.items', \App\Http\Controllers\Web\PurchaseOrderItemController::class)->except(['index', 'show']);
Route::post('purchase-orders/{purchaseOrder}/submit', [\App\Http\Controllers\Web\PurchaseOrderController::class, 'submit'])->name('purchase-orders.submit');
Route::post('purchase-orders/{purchaseOrder}/receive', [\App\Http\Controllers\Web\PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
Route::resource('production-plans', \App\Http\Controllers\Web\ProductionPlanController::class);

Route::post('production-plans/{productionPlan}/submit', [\App\Http\Controllers\Web\ProductionPlanController::class, 'submit'])->name('production-plans.submit');
Route::post('production-plans/{productionPlan}/approve', [\App\Http\Controllers\Web\ProductionPlanController::class, 'approve'])->name('production-plans.approve');
Route::post('production-plans/{productionPlan}/reject', [\App\Http\Controllers\Web\ProductionPlanController::class, 'reject'])->name('production-plans.reject');

Route::post('production-plans/{productionPlan}/generate-po', [\App\Http\Controllers\Web\ProductionPlanController::class, 'generatePurchaseOrders'])
    ->name('production-plans.generate-po');

Route::get('production-plan-items/{item}/results/create', [\App\Http\Controllers\Web\ProductionResultController::class, 'create'])
    ->name('production-results.create');
Route::post('production-results', [\App\Http\Controllers\Web\ProductionResultController::class, 'store'])
    ->name('production-results.store');
