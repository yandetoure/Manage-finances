<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RevenueController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\SavingController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\BudgetController;

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
    Route::apiResource('debts', \App\Http\Controllers\Api\DebtController::class);
    Route::apiResource('claims', \App\Http\Controllers\Api\ClaimController::class);

    Route::get('/settings', [\App\Http\Controllers\Api\SettingsController::class, 'index']);
    Route::post('/settings', [\App\Http\Controllers\Api\SettingsController::class, 'update']);

    // Payments
    Route::get('/debts/{id}/payments', [\App\Http\Controllers\Api\DebtPaymentController::class, 'index']);
    Route::post('/debts/payments', [\App\Http\Controllers\Api\DebtPaymentController::class, 'store']); // Consistent with web controller store

    Route::get('/claims/{id}/payments', [\App\Http\Controllers\Api\ClaimPaymentController::class, 'index']);
    Route::post('/claims/payments', [\App\Http\Controllers\Api\ClaimPaymentController::class, 'store']);

    // Budgets
    Route::get('/budgets', [BudgetController::class, 'index']);
    Route::post('/budgets', [BudgetController::class, 'store']);
    Route::delete('/budgets/{id}', [BudgetController::class, 'destroy']);
    Route::get('/budgets/summary', [BudgetController::class, 'summary']);
});
