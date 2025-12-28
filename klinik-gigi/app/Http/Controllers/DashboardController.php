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
        
        // Auto Update status jadwals yang sudah lewat
        \App\Models\Jadwal::autoUpdateStatus();

        // Get recent bookings (PRESENT status only, ordered by newest)
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

        // New: Economic Statistic
        $totalPendapatan = \App\Models\Pembayaran::whereMonth('TanggalPembayaran', now()->month)
            ->whereYear('TanggalPembayaran', now()->year)
            ->where('Status', 'PAID')
            ->sum('TotalBayar');

        // 1. Service Stats (Top 5 Tindakan)
        $topTindakan = \Illuminate\Support\Facades\DB::table('rekammedis_tindakan')
            ->join('tindakan', 'rekammedis_tindakan.IdTindakan', '=', 'tindakan.IdTindakan')
            ->select('tindakan.NamaTindakan', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('tindakan.IdTindakan', 'tindakan.NamaTindakan')
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get();
        
        $totalPemeriksaan = \App\Models\RekamMedis::count();

        // 2. Audit Activities
        $auditUsers = User::orderBy('created_at', 'desc')->limit(3)->get()->map(function($user) {
            return [
                'icon' => 'fa-user-plus',
                'color' => 'success',
                'title' => 'User Baru Terdaftar',
                'desc' => $user->name . ' (' . ucfirst($user->role) . ')',
                'time' => $user->created_at ? $user->created_at->diffForHumans() : '-'
            ];
        });

        $auditRM = \App\Models\RekamMedis::with(['dokter', 'pasien'])->orderBy('IdRekamMedis', 'desc')->limit(3)->get()->map(function($rm) {
            return [
                'icon' => 'fa-file-pen',
                'color' => 'primary',
                'title' => 'Input Rekam Medis',
                'desc' => ($rm->dokter->Nama ?? '-') . ' memeriksa ' . ($rm->pasien->Nama ?? '-'),
                'time' => \Carbon\Carbon::parse($rm->Tanggal)->diffForHumans()
            ];
        });

        $auditActivities = $auditUsers->concat($auditRM)->take(3);
        
        return view('dashboards.admin', compact(
            'recentBookings', 
            'totalPasien', 
            'totalBookingToday', 
            'totalDokter', 
            'totalPendapatan',
            'topTindakan',
            'totalPemeriksaan',
            'auditActivities'
        ));
    }

    public function dokter()
    {
        $this->authorizeRole('dokter');
        
        $user = Auth::user();
        $dokter = $user->pegawai; 

        // Default empty state
        $jadwalHariIni = collect([]); // Collection of jadwals
        $antrian = collect([]);
        $totalPasienSelesai = 0;
        $totalPasienMenunggu = 0;

        if ($dokter) {
            // Get ALL Jadwal Hari Ini (Pagi & Sore combined)
            $jadwalHariIni = \App\Models\Jadwal::with(['bookings.pasien'])
                ->where('IdDokter', $dokter->PegawaiID)
                ->whereDate('Tanggal', today())
                ->where('Status', '!=', 'Cancelled')
                ->orderBy('JamMulai', 'asc')
                ->get();

            if ($jadwalHariIni->isNotEmpty()) {
                // Merge bookings from all sessions
                $antrian = $jadwalHariIni->flatMap(function ($jadwal) {
                    return $jadwal->bookings->where('Status', '!=', 'CANCELLED');
                })->sortBy('TanggalBooking'); // Or sort by time preference if needed

                $totalPasienMenunggu = $antrian->where('Status', 'PRESENT')->count();
                $totalPasienSelesai = $antrian->where('Status', 'COMPLETED')->count(); 
            }
        }

        return view('dashboards.dokter', compact('dokter', 'jadwalHariIni', 'antrian', 'totalPasienSelesai', 'totalPasienMenunggu'));
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
