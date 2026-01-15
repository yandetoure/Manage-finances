<?php

use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\RevenueController;
use App\Http\Controllers\User\ExpenseController;
use App\Http\Controllers\User\DebtController;
use App\Http\Controllers\User\ClaimController;
use App\Http\Controllers\User\SavingController;
use App\Http\Controllers\User\ForecastController;
use App\Http\Controllers\User\DebtPaymentController;
use App\Http\Controllers\User\SavingContributionController;
use App\Http\Controllers\User\ClaimPaymentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\ModuleController as AdminModule;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Temp Login Route for testing
Route::get('/login-test', function () {
    $user = User::where('email', '=', 'user@manage.com')->first();
    Auth::login($user);
    return redirect()->route('home');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

    Route::resource('revenues', RevenueController::class)->middleware('module.active:revenues');
    Route::resource('expenses', ExpenseController::class)->middleware('module.active:expenses');
    Route::resource('debts', DebtController::class)->middleware('module.active:debts');
    Route::resource('claims', ClaimController::class)->middleware('module.active:claims');
    Route::resource('savings', SavingController::class)->middleware('module.active:savings');
    Route::post('savings/contribute', [SavingContributionController::class, 'store'])->name('savings.contribute');
    Route::resource('forecasts', ForecastController::class)->middleware('module.active:forecasts');

    Route::post('debts/pay', [DebtPaymentController::class, 'store'])->name('debts.pay');
    Route::post('debts/{debt}/status', [DebtController::class, 'updateStatus'])->name('debts.update-status');
    Route::post('claims/pay', [ClaimPaymentController::class, 'store'])->name('claims.pay');
    Route::post('claims/{claim}/status', [ClaimController::class, 'updateStatus'])->name('claims.update-status');
    Route::post('claims/{claim}/toggle-paid', [ClaimController::class, 'togglePaid'])->name('claims.toggle-paid');

    // Transactions (Unified view)
    Route::get('/transactions', function () {
        $revenues = \App\Models\Revenue::where('user_id', '=', auth()->id())->get();
        $expenses = \App\Models\Expense::where('user_id', '=', auth()->id())->get();
        return view('mobile.transactions', compact('revenues', 'expenses'));
    })->name('transactions');

    Route::get('/analytics', [HomeController::class, 'analytics'])->name('analytics');
    Route::get('/settings', [HomeController::class, 'settings'])->name('settings');
    Route::post('/settings', [HomeController::class, 'updateSettings'])->name('settings.update');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUser::class);
    Route::get('/modules', [AdminModule::class, 'index'])->name('modules.index');
    Route::post('/modules/toggle', [AdminModule::class, 'toggle'])->name('modules.toggle');
});