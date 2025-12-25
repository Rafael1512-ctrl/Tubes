<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\TindakanSpesialisController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [LandingController::class, 'index'])->name('landing.home');
Route::get('/booking-online', [LandingController::class, 'booking'])->name('landing.booking');
Route::post('/booking-online', [LandingController::class, 'storeBooking'])->name('landing.booking.store');

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard - untuk semua role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Jadwal - untuk semua yang login
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    
    // Booking
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

    // Tindakan Spesialis - Dokter & Admin dapat create/update, Pasien view only
    Route::get('/tindakan-spesialis', [TindakanSpesialisController::class, 'index'])->name('tindakan-spesialis.index');
    Route::get('/tindakan-spesialis/{id}', [TindakanSpesialisController::class, 'show'])->name('tindakan-spesialis.show');
    
    // Admin Routes
    Route::middleware(['role:admin'])->group(function () {
        // Pasien Management
        Route::get('/pasien', [PasienController::class, 'index'])->name('pasien.index');
        Route::get('/pasien/create', [PasienController::class, 'create'])->name('pasien.create');
        Route::post('/pasien', [PasienController::class, 'store'])->name('pasien.store');
        Route::get('/pasien/{id}', [PasienController::class, 'show'])->name('pasien.show');
        Route::get('/pasien/{id}/edit', [PasienController::class, 'edit'])->name('pasien.edit');
        Route::put('/pasien/{id}', [PasienController::class, 'update'])->name('pasien.update');
        Route::delete('/pasien/{id}', [PasienController::class, 'destroy'])->name('pasien.destroy');

        // Pegawai/Dokter Management
        Route::get('/admin/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
        Route::get('/admin/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
        Route::post('/admin/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
        Route::get('/admin/pegawai/{id}', [PegawaiController::class, 'show'])->name('pegawai.show');
        Route::get('/admin/pegawai/{id}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
        Route::put('/admin/pegawai/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
        Route::delete('/admin/pegawai/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

        // Obat Management
        Route::get('/obat', [ObatController::class, 'index'])->name('obat.index');
        Route::get('/obat/create', [ObatController::class, 'create'])->name('obat.create');
        Route::post('/obat', [ObatController::class, 'store'])->name('obat.store');
        Route::get('/obat/{id}/edit', [ObatController::class, 'edit'])->name('obat.edit');
        Route::put('/obat/{id}', [ObatController::class, 'update'])->name('obat.update');
        Route::delete('/obat/{id}', [ObatController::class, 'destroy'])->name('obat.destroy');
        Route::get('/api/obat/check-expiry', [ObatController::class, 'checkExpiry'])->name('obat.check-expiry');

        // Pembayaran Routes
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('/pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
        Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
        Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    });

    // Dokter & Admin Routes
    Route::middleware(['role:dokter,admin'])->group(function () {
        // Rekam Medis
        Route::get('/rekam-medis', [RekamMedisController::class, 'index'])->name('rekam-medis.index');
        Route::get('/rekam-medis/create', [RekamMedisController::class, 'create'])->name('rekam-medis.create');
        Route::post('/rekam-medis', [RekamMedisController::class, 'store'])->name('rekam-medis.store');
        Route::get('/rekam-medis/{id}', [RekamMedisController::class, 'show'])->name('rekam-medis.show');

        // Tindakan Spesialis - Create/Update
        Route::get('/tindakan-spesialis/create', [TindakanSpesialisController::class, 'create'])->name('tindakan-spesialis.create');
        Route::post('/tindakan-spesialis', [TindakanSpesialisController::class, 'store'])->name('tindakan-spesialis.store');
        Route::post('/tindakan-spesialis/session/{sesiId}/update', [TindakanSpesialisController::class, 'updateSession'])->name('tindakan-spesialis.session.update');
        Route::post('/tindakan-spesialis/session/{sesiId}/reschedule', [TindakanSpesialisController::class, 'rescheduleSession'])->name('tindakan-spesialis.session.reschedule');
        Route::post('/tindakan-spesialis/session/{sesiId}/cancel', [TindakanSpesialisController::class, 'cancelSession'])->name('tindakan-spesialis.session.cancel');
    });
});
