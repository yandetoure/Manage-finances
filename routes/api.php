<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RevenueController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\SavingController;
use App\Http\Controllers\Api\DashboardController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::apiResource('revenues', RevenueController::class);
    Route::apiResource('expenses', ExpenseController::class);
    Route::apiResource('savings', SavingController::class);
});
