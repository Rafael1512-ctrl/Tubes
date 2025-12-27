<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;

// Landing page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard per role
// Dashboard per role
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin']);
    Route::get('/dokter/dashboard', [DashboardController::class, 'dokter']);
    Route::get('/pasien/dashboard', [DashboardController::class, 'pasien']);

    // Tambahan fitur khusus admin
    Route::prefix('admin')->group(function () {
        // User Management
        Route::get('/users', [AdminController::class, 'index'])->name('admin.users');
        Route::get('/users/create', [AdminController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [AdminController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{id}', [AdminController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
        
        // Jadwal Management
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('admin.jadwal');
        Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('admin.jadwal.create');
        Route::post('/jadwal', [JadwalController::class, 'store'])->name('admin.jadwal.store');
        Route::get('/jadwal/{id}/edit', [JadwalController::class, 'edit'])->name('admin.jadwal.edit');
        Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('admin.jadwal.update');
        Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('admin.jadwal.destroy');
        Route::get('/jadwal/date/{date}', [JadwalController::class, 'getByDate'])->name('admin.jadwal.bydate');
        
        // Booking Management
        Route::get('/booking', [BookingController::class, 'index'])->name('admin.booking');
        Route::get('/booking/create', [BookingController::class, 'create'])->name('admin.booking.create');
        Route::post('/booking', [BookingController::class, 'store'])->name('admin.booking.store');
        Route::get('/booking/{id}/edit', [BookingController::class, 'edit'])->name('admin.booking.edit');
        Route::put('/booking/{id}', [BookingController::class, 'update'])->name('admin.booking.update');
        Route::delete('/booking/{id}', [BookingController::class, 'destroy'])->name('admin.booking.destroy');
    });
});

