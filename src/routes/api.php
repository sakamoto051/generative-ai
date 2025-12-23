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
    Route::apiResource('products', \App\Http\Controllers\Api\ProductController::class);
});