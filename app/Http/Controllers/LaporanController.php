<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }
    
    public function harian(Request $request)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');
        
        $data = [
            'tanggal' => $tanggal,
            'pasien_baru' => DB::table('Pasien')->whereDate('created_at', $tanggal)->count(),
            'booking' => DB::table('Booking')->whereDate('TanggalBooking', $tanggal)->count(),
            'rekam_medis' => DB::table('RekamMedis')->whereDate('Tanggal', $tanggal)->count(),
            'pembayaran' => DB::table('Pembayaran')
                ->whereDate('TanggalPembayaran', $tanggal)
                ->where('Status', 'PAID')
                ->sum('TotalBayar'),
        ];
        
        return view('laporan.harian', $data);
    }
    
    public function bulanan(Request $request)
    {
        $bulan = $request->bulan ?? date('Y-m');
        
        $data = [
            'bulan' => $bulan,
            'pasien_baru' => DB::table('Pasien')->whereMonth('created_at', date('m', strtotime($bulan)))->count(),
            'booking' => DB::table('Booking')->whereMonth('TanggalBooking', date('m', strtotime($bulan)))->count(),
            'rekam_medis' => DB::table('RekamMedis')->whereMonth('Tanggal', date('m', strtotime($bulan)))->count(),
            'pembayaran' => DB::table('Pembayaran')
                ->whereMonth('TanggalPembayaran', date('m', strtotime($bulan)))
                ->where('Status', 'PAID')
                ->sum('TotalBayar'),
        ];
        
        return view('laporan.bulanan', $data);
    }
}