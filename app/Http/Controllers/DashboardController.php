<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        if ($user->role === 'admin') {
            $data['total_pasien'] = \App\Models\Pasien::count();
            $data['total_dokter'] = \App\Models\Pegawai::where('Jabatan', 'Dokter')->count();
            $data['booking_hari_ini'] = \App\Models\Booking::whereDate('TanggalBooking', today())->count();
            $data['total_income'] = 0; // Nanti diimplementasikan

            $data['latest_bookings'] = \App\Models\Booking::with(['pasien', 'jadwal.dokter'])
                                        ->latest('TanggalBooking')
                                        ->take(5)
                                        ->get();
        } 
        elseif ($user->role === 'dokter') {
            $dokter = $user->pegawai;
            
            if ($dokter) {
                // Jadwal Hari Ini (Tanggal specific)
                $data['jadwal_hari_ini'] = \App\Models\Jadwal::where('IdDokter', $dokter->PegawaiID)
                                            ->whereDate('Tanggal', today())
                                            ->first();
                
                // Pasien Hari Ini
                $data['pasien_hari_ini'] = \App\Models\Booking::whereHas('jadwal', function($q) use ($dokter) {
                                                $q->where('IdDokter', $dokter->PegawaiID);
                                            })
                                            ->whereDate('TanggalBooking', today())
                                            ->count();
                                            
                $data['appointments'] = \App\Models\Booking::with('pasien')
                                        ->whereHas('jadwal', function($q) use ($dokter) {
                                            $q->where('IdDokter', $dokter->PegawaiID);
                                        })
                                        ->whereDate('TanggalBooking', today())
                                        ->get();
            }
        } 
        elseif ($user->role === 'pasien') {
            $pasien = $user->pasien;
            
            if ($pasien) {
                // Next appointment
                $data['next_appointment'] = \App\Models\Booking::with(['jadwal.dokter'])
                                            ->where('PasienID', $pasien->PasienID)
                                            ->where('Status', '!=', 'CANCELLED')
                                            ->where('Status', '!=', 'SELESAI') // Adjust status logic as needed
                                            ->whereDate('TanggalBooking', '>=', today())
                                            ->orderBy('TanggalBooking')
                                            ->first();
                                            
                $data['riwayat'] = \App\Models\Booking::with(['jadwal.dokter'])
                                    ->where('PasienID', $pasien->PasienID)
                                    ->latest('TanggalBooking')
                                    ->take(5)
                                    ->get();
            }
        }

        return view('dashboard.index', compact('data'));
    }
}