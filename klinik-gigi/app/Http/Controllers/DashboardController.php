<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\User;
use App\Models\Pasien;

class DashboardController extends Controller
{
    public function admin()
    {
        $this->authorizeRole('admin');
        
        // Get recent bookings (PRESENT status, ordered by newest)
        $recentBookings = Booking::with(['jadwal.dokter', 'pasien'])
            ->where('Status', 'PRESENT')
            ->orderBy('TanggalBooking', 'desc')
            ->limit(10)
            ->get();
        
        // Get statistics
        $totalPasien = Pasien::count();
        $totalBookingToday = Booking::whereDate('TanggalBooking', today())
            ->where('Status', 'PRESENT')
            ->count();
        $totalDokter = User::where('role', 'dokter')->count();
        
        return view('dashboards.admin', compact('recentBookings', 'totalPasien', 'totalBookingToday', 'totalDokter'));
    }

    public function dokter()
    {
        $this->authorizeRole('dokter');
        return view('dashboards.dokter');
    }

    public function pasien()
    {
        $this->authorizeRole('pasien');
        return view('dashboards.pasien');
    }

    private function authorizeRole(string $role)
    {
        $user = Auth::user();
        abort_unless($user && $user->role === $role, 403, 'Unauthorized');
    }
}
