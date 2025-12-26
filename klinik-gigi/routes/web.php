<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;

// Default route ke login
// Tampilkan form login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::redirect('/', '/login');

// Dashboard per role
// Dashboard per role
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin']);
    Route::get('/dokter/dashboard', [DashboardController::class, 'dokter']);
    Route::get('/pasien/dashboard', [DashboardController::class, 'pasien']);

    // Tambahan fitur khusus admin
    Route::prefix('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'index'])->name('admin.users');
        Route::get('/users/create', [AdminController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [AdminController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{id}', [AdminController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    });
});

