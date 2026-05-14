<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReportController;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/theme', [AuthController::class, 'setTheme'])->name('theme.set');

// Lupa & ganti sandi
Route::get('/forgot-password', [PasswordController::class, 'requestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordController::class, 'verifyEmail'])->name('password.verify');
Route::get('/reset-password', [PasswordController::class, 'resetForm'])->name('password.reset.form');
Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.reset');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Settings (admin only)
    Route::middleware('admin')->group(function () {
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::resource('users', UserController::class)->except(['show']);
    });

    // Customers
    Route::resource('customers', CustomerController::class)->except(['show']);

    // Fields
    Route::resource('fields', FieldController::class)->except(['show']);

    // Bookings
    Route::get('/bookings/check-availability', [BookingController::class, 'checkAvailability'])->name('bookings.availability');
    Route::patch('/bookings/{booking}/payment', [BookingController::class, 'updatePayment'])->name('bookings.payment');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
    Route::resource('bookings', BookingController::class)->except(['edit', 'update']);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
