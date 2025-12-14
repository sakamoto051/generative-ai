<?php

use App\Http\Controllers\BomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionPlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 製品マスタ
    Route::resource('products', ProductController::class);

    // 材料マスタ
    Route::resource('materials', MaterialController::class);

    // BOMマスタ
    Route::resource('boms', BomController::class);

    // 設備マスタ
    Route::resource('equipment', EquipmentController::class);

    // 作業者マスタ
    Route::resource('workers', WorkerController::class);

    // 生産計画
    Route::resource('production-plans', ProductionPlanController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
