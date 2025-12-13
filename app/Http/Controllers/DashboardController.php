<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Data statistik sederhana
        $stats = [
            'total_pasien' => DB::table('Pasien')->count(),
            'total_dokter' => DB::table('Pegawai')->where('Jabatan', 'like', '%Dokter%')->count(),
            'booking_hari_ini' => DB::table('Booking')->whereDate('TanggalBooking', today())->count(),
        ];
        
        // Booking hari ini
        $bookingsToday = DB::table('Booking')
            ->join('Jadwal', 'Booking.IdJadwal', '=', 'Jadwal.IdJadwal')
            ->join('Pasien', 'Booking.PasienID', '=', 'Pasien.PasienID')
            ->join('Pegawai', 'Jadwal.IdDokter', '=', 'Pegawai.PegawaiID')
            ->whereDate('Booking.TanggalBooking', today())
            ->select('Booking.*', 'Pasien.Nama as nama_pasien', 'Pegawai.Nama as nama_dokter', 
                     'Jadwal.Tanggal', 'Jadwal.JamMulai', 'Jadwal.JamAkhir')
            ->orderBy('Jadwal.JamMulai')
            ->get();
        
        return view('dashboard.index', [
            'stats' => $stats,
            'bookingsToday' => $bookingsToday
        ]);
    }
}