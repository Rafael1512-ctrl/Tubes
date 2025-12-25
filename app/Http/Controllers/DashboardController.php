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

            // Obat expired dan expiring soon
            $data['obat_expired'] = \App\Models\Obat::expired()->count();
            $data['obat_expiring_soon'] = \App\Models\Obat::expiringSoon(30)->count();
            $data['obat_expiring_list'] = \App\Models\Obat::expiringSoon(30)->take(5)->get();
            
            // Kontrol berkala stats
            $data['active_tindakan_spesialis'] = \App\Models\TindakanSpesialis::where('status', 'active')->count();
            $data['completed_tindakan_spesialis'] = \App\Models\TindakanSpesialis::where('status', 'completed')->count();

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
                
                // Kontrol berkala hari ini
                $data['kontrol_berkala_hari_ini'] = \App\Models\SesiTindakan::whereHas('tindakanSpesialis', function($q) use ($dokter) {
                                                        $q->where('DokterID', $dokter->PegawaiID);
                                                    })
                                                    ->whereDate('scheduled_date', today())
                                                    ->where('status', 'scheduled')
                                                    ->count();
                
                // Upcoming sesi dalam 7 hari
                $data['upcoming_sesi'] = \App\Models\SesiTindakan::with(['tindakanSpesialis.pasien'])
                                        ->whereHas('tindakanSpesialis', function($q) use ($dokter) {
                                            $q->where('DokterID', $dokter->PegawaiID);
                                        })
                                        ->where('status', 'scheduled')
                                        ->whereBetween('scheduled_date', [today(), today()->addDays(7)])
                                        ->orderBy('scheduled_date')
                                        ->take(5)
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
                
                // Next kontrol berkala
                $data['next_kontrol_berkala'] = \App\Models\SesiTindakan::with(['tindakanSpesialis.dokter'])
                                                ->whereHas('tindakanSpesialis', function($q) use ($pasien) {
                                                    $q->where('PasienID', $pasien->PasienID)
                                                      ->where('status', 'active');
                                                })
                                                ->where('status', 'scheduled')
                                                ->where('scheduled_date', '>=', today())
                                                ->orderBy('scheduled_date')
                                                ->first();
                
                // Riwayat kontrol berkala
                $data['riwayat_kontrol'] = \App\Models\TindakanSpesialis::with('dokter')
                                          ->where('PasienID', $pasien->PasienID)
                                          ->latest('created_at')
                                          ->take(3)
                                          ->get();
            }
        }

        return view('dashboard.index', compact('data'));
    }
}