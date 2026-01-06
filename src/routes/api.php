<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user()->load('role');
    });

    // Admin protected routes
    Route::middleware(['role:System Administrator'])->get('/admin/dashboard', function () {
        return response()->json(['message' => 'Welcome to Admin Dashboard']);
    });

    // Production Manager protected routes
    Route::middleware(['role:Production Manager'])->get('/planner/plans', function () {
        return response()->json(['message' => 'Production Plans List']);
    });

    // Manufacturing Leader protected routes
    Route::middleware(['role:Manufacturing Leader'])->get('/factory/execution', function () {
        return response()->json(['message' => 'Factory Floor Execution Interface']);
    });

    // Cost Accountant protected routes
    Route::middleware(['role:Cost Accountant'])->get('/accounting/reports', function () {
        return response()->json(['message' => 'Financial Reports and Cost Analysis']);
    });

    // Product routes
    Route::get('/products', [AuthController::class, 'index']); // Wait, index is in ProductController

    Route::middleware(['role:System Administrator,Production Manager,Manufacturing Leader,Cost Accountant'])->group(function () {
        Route::post('/products', [\App\Http\Controllers\Api\ProductController::class, 'store']);
        Route::put('/products/{product}', [\App\Http\Controllers\Api\ProductController::class, 'update']);
        Route::patch('/products/{product}', [\App\Http\Controllers\Api\ProductController::class, 'update']);
        Route::delete('/products/{product}', [\App\Http\Controllers\Api\ProductController::class, 'destroy']);

        Route::post('/materials', [\App\Http\Controllers\Api\MaterialController::class, 'store']);
        Route::put('/materials/{material}', [\App\Http\Controllers\Api\MaterialController::class, 'update']);
        Route::patch('/materials/{material}', [\App\Http\Controllers\Api\MaterialController::class, 'update']);
        Route::delete('/materials/{material}', [\App\Http\Controllers\Api\MaterialController::class, 'destroy']);
    });

    Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);
    Route::get('/products/{product}', [\App\Http\Controllers\Api\ProductController::class, 'show']);
    Route::get('/products/{product}/bom-tree', [\App\Http\Controllers\Api\ProductController::class, 'bomTree']);
    Route::get('/materials', [\App\Http\Controllers\Api\MaterialController::class, 'index']);
    Route::get('/materials/{material}', [\App\Http\Controllers\Api\MaterialController::class, 'show']);

    // BOM routes
    Route::middleware(['role:System Administrator,Production Manager,Manufacturing Leader'])->group(function () {
        Route::post('/boms', [\App\Http\Controllers\Api\BomController::class, 'store']);
        Route::put('/boms/{bom}', [\App\Http\Controllers\Api\BomController::class, 'update']);
        Route::patch('/boms/{bom}', [\App\Http\Controllers\Api\BomController::class, 'update']);
        Route::delete('/boms/{bom}', [\App\Http\Controllers\Api\BomController::class, 'destroy']);
    });

    Route::get('/boms', [\App\Http\Controllers\Api\BomController::class, 'index']);
    Route::get('/boms/{bom}', [\App\Http\Controllers\Api\BomController::class, 'show']);

    // Inventory routes
    Route::middleware(['role:System Administrator,Production Manager,Manufacturing Leader'])->group(function () {
        Route::post('/inventories', [\App\Http\Controllers\Api\InventoryController::class, 'store']);
    });

    // MRP routes
    Route::middleware(['role:System Administrator,Production Manager,Manufacturing Leader'])->group(function () {
        Route::post('/mrp/calculate', [\App\Http\Controllers\Api\MrpController::class, 'calculate']);
    });
});
