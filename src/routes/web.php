<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
