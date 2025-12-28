<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pasien;
use App\Models\Booking;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->query('year', date('Y'));
        
        // 1. Monthly Revenue Data for Chart
        $revenueData = Pembayaran::select(
                DB::raw('MONTH(TanggalPembayaran) as month'),
                DB::raw('SUM(TotalBayar) as total')
            )
            ->whereYear('TanggalPembayaran', $year)
            ->where('Status', 'PAID')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->all();

        // Fill missing months with 0
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenue[] = $revenueData[$i] ?? 0;
        }

        // 2. Summary Stats
        $totalRevenueYear = array_sum($monthlyRevenue);
        $totalPasienNew = Pasien::whereHas('user', function($q) use ($year) {
            $q->whereYear('created_at', $year);
        })->count();
        $totalPemeriksaan = RekamMedis::whereYear('Tanggal', $year)->count();

        // 3. Most Popular Procedures (Tindakan)
        $popularTindakan = DB::table('rekammedis_tindakan')
            ->join('tindakan', 'rekammedis_tindakan.IdTindakan', '=', 'tindakan.IdTindakan')
            ->select('tindakan.NamaTindakan', DB::raw('count(*) as total'))
            ->groupBy('tindakan.IdTindakan', 'tindakan.NamaTindakan')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return view('admin.laporan.index', compact(
            'monthlyRevenue', 
            'totalRevenueYear', 
            'totalPasienNew', 
            'totalPemeriksaan',
            'popularTindakan',
            'year'
        ));
    }

    public function downloadPDF(Request $request)
    {
        $year = $request->query('year', date('Y'));
        
        // Data for PDF (same as index but simplified for paper)
        $revenueData = Pembayaran::select(
                DB::raw('MONTH(TanggalPembayaran) as month'),
                DB::raw('SUM(TotalBayar) as total')
            )
            ->whereYear('TanggalPembayaran', $year)
            ->where('Status', 'PAID')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $totalRevenueYear = $revenueData->sum('total');
        $totalPasienNew = Pasien::whereHas('user', function($q) use ($year) {
            $q->whereYear('created_at', $year);
        })->count();
        $totalPemeriksaan = RekamMedis::whereYear('Tanggal', $year)->count();

        $popularTindakan = DB::table('rekammedis_tindakan')
            ->join('tindakan', 'rekammedis_tindakan.IdTindakan', '=', 'tindakan.IdTindakan')
            ->select('tindakan.NamaTindakan', DB::raw('count(*) as total'))
            ->groupBy('tindakan.IdTindakan', 'tindakan.NamaTindakan')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'revenueData', 
            'totalRevenueYear', 
            'totalPasienNew', 
            'totalPemeriksaan',
            'popularTindakan',
            'year'
        ));

        return $pdf->download("Laporan-Tahunan-{$year}.pdf");
    }
}
