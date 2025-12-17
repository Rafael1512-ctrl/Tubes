<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Pasien
    Route::get('/pasien', [PasienController::class, 'index'])->name('pasien.index');
    Route::get('/pasien/create', [PasienController::class, 'create'])->name('pasien.create');
    Route::post('/pasien', [PasienController::class, 'store'])->name('pasien.store');
    Route::get('/pasien/{id}', [PasienController::class, 'show'])->name('pasien.show');
    
    // Jadwal
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    
    // Booking
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

    Route::get('/pasien/{id}/edit', [PasienController::class, 'edit'])->name('pasien.edit');
    Route::put('/pasien/{id}', [PasienController::class, 'update'])->name('pasien.update');
    Route::delete('/pasien/{id}', [PasienController::class, 'destroy'])->name('pasien.destroy');

    Route::middleware(['auth', 'role:admin'])->group(function () {
    // ... route admin lainnya ...
    
    // Pegawai (Dokter) routes
    Route::get('/admin/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/admin/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
    Route::post('/admin/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::get('/admin/pegawai/{id}', [PegawaiController::class, 'show'])->name('pegawai.show');
    Route::get('/admin/pegawai/{id}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::put('/admin/pegawai/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/admin/pegawai/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

    // Rekam Medis routes
    Route::middleware(['auth', 'role:admin,dokter'])->group(function () {
    Route::get('/rekam-medis', [RekamMedisController::class, 'index'])->name('rekam-medis.index');
    Route::get('/rekam-medis/create', [RekamMedisController::class, 'create'])->name('rekam-medis.create');
    Route::post('/rekam-medis', [RekamMedisController::class, 'store'])->name('rekam-medis.store');
    Route::get('/rekam-medis/{id}', [RekamMedisController::class, 'show'])->name('rekam-medis.show');
    // Pembayaran routes
Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
});
});
});
});