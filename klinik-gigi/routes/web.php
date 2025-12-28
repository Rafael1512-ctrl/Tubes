<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Landing page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', function() { return redirect('/'); });
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    // Setelah verifikasi, arahkan ke dashboard yang sesuai
    $role = auth()->user()->role;
    return redirect($role . '/dashboard')->with('success', 'Email berhasil diverifikasi!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi baru telah dikirim!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Dashboard per role
// Dashboard per role
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/dokter/dashboard', [DashboardController::class, 'dokter'])->name('dokter.dashboard');
    Route::get('/pasien/dashboard', [DashboardController::class, 'pasien'])->name('pasien.dashboard');

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

        // Pembayaran Management (New)
        Route::get('/pembayaran', [App\Http\Controllers\PembayaranController::class, 'index'])->name('admin.pembayaran');
        Route::get('/pembayaran/create/{id}', [App\Http\Controllers\PembayaranController::class, 'create'])->name('admin.pembayaran.create');
        Route::post('/pembayaran', [App\Http\Controllers\PembayaranController::class, 'store'])->name('admin.pembayaran.store');

        // Patient Data (New)
        Route::get('/pasien', [AdminController::class, 'pasien'])->name('admin.pasien');
        Route::get('/pasien/{id}/history', [AdminController::class, 'history'])->name('admin.pasien.history');

        // Obat Management (New)
        Route::get('/obat', [App\Http\Controllers\ObatController::class, 'index'])->name('admin.obat');
        Route::get('/obat/create', [App\Http\Controllers\ObatController::class, 'create'])->name('admin.obat.create');
        Route::post('/obat', [App\Http\Controllers\ObatController::class, 'store'])->name('admin.obat.store');
        Route::get('/obat/{id}/edit', [App\Http\Controllers\ObatController::class, 'edit'])->name('admin.obat.edit');
        Route::put('/obat/{id}', [App\Http\Controllers\ObatController::class, 'update'])->name('admin.obat.update');
        Route::delete('/obat/{id}', [App\Http\Controllers\ObatController::class, 'destroy'])->name('admin.obat.destroy');

        // Laporan (New)
        Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('admin.laporan');
        Route::get('/laporan/download', [App\Http\Controllers\LaporanController::class, 'downloadPDF'])->name('admin.laporan.pdf');
    });

    // Fitur khusus Dokter
    Route::prefix('dokter')->group(function () {
        Route::get('/rekam-medis/create/{idBooking}', [App\Http\Controllers\RekamMedisController::class, 'create'])->name('dokter.rekam-medis.create');
        Route::post('/rekam-medis', [App\Http\Controllers\RekamMedisController::class, 'store'])->name('dokter.rekam-medis.store');
        
        // Jadwal Saya
        Route::get('/jadwal', [App\Http\Controllers\DokterController::class, 'jadwal'])->name('dokter.jadwal');

        // Data Pasien & History
        Route::get('/pasien', [App\Http\Controllers\DokterController::class, 'pasien'])->name('dokter.pasien');
        Route::get('/pasien/{id}/history', [App\Http\Controllers\DokterController::class, 'history'])->name('dokter.pasien.history');

        // Riwayat Praktek
        Route::get('/riwayat', [App\Http\Controllers\DokterController::class, 'riwayat'])->name('dokter.riwayat');
    });
});

