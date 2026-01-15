<?php

use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\RevenueController;
use App\Http\Controllers\User\ExpenseController;
use App\Http\Controllers\User\DebtController;
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
    $user = User::where('email', 'user@manage.com')->first();
    Auth::login($user);
    return redirect()->route('home');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

    Route::resource('revenues', RevenueController::class)->middleware('module.active:revenues');
    Route::resource('expenses', ExpenseController::class)->middleware('module.active:expenses');
    Route::resource('debts', DebtController::class)->middleware('module.active:debts');

    Route::get('/settings', function () {
        return view('mobile.settings');
    })->name('settings');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUser::class);
    Route::get('/modules', [AdminModule::class, 'index'])->name('modules.index');
    Route::post('/modules/toggle', [AdminModule::class, 'toggle'])->name('modules.toggle');
});