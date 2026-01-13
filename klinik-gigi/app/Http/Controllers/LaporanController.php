<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pasien;
use App\Models\Booking;
use App\Models\RekamMedis;
use App\Models\Obat;
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
        $totalPasienNew = Pasien::whereHas('user', function ($q) use ($year) {
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

        // 4. Medicine Cost Calculation (COGS) - dari obat_log untuk pembelian
        $medicineCost = DB::table('obat_log')
            ->join('obat', 'obat_log.IdObat', '=', 'obat.IdObat')
            ->where('obat_log.Aksi', 'MASUK')
            ->whereYear('obat_log.Tanggal', $year)
            ->select(DB::raw('SUM(obat_log.Jumlah * obat.HargaBeli) as total_cost'))
            ->first()
            ->total_cost ?? 0;

        $estimatedProfit = $totalRevenueYear - $medicineCost;

        return view('admin.laporan.index', compact(
            'monthlyRevenue',
            'totalRevenueYear',
            'totalPasienNew',
            'totalPemeriksaan',
            'popularTindakan',
            'medicineCost',
            'estimatedProfit',
            'year'
        ));
    }

    /**
     * Laporan Keuangan (Pengeluaran & Pendapatan)
     */
    public function keuangan(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', null);

        // Pendapatan from Pembayaran
        $pendapatanQuery = Pembayaran::where('Status', 'PAID')
            ->whereYear('TanggalPembayaran', $year);

        if ($month) {
            $pendapatanQuery->whereMonth('TanggalPembayaran', $month);
        }

        $pendapatanKotor = $pendapatanQuery->sum('TotalBayar');

        // Pendapatan per bulan
        $pendapatanBulanan = Pembayaran::select(
            DB::raw('MONTH(TanggalPembayaran) as bulan'),
            DB::raw('SUM(TotalBayar) as total')
        )
            ->where('Status', 'PAID')
            ->whereYear('TanggalPembayaran', $year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Pengeluaran (Modal Obat) from obat_log
        $pengeluaranQuery = DB::table('obat_log')
            ->join('obat', 'obat_log.IdObat', '=', 'obat.IdObat')
            ->where('obat_log.Aksi', 'MASUK')
            ->whereYear('obat_log.Tanggal', $year);

        if ($month) {
            $pengeluaranQuery->whereMonth('obat_log.Tanggal', $month);
        }

        $pengeluaran = $pengeluaranQuery->select(DB::raw('SUM(obat_log.Jumlah * obat.HargaBeli) as total'))->first()->total ?? 0;

        // Pengeluaran per bulan
        $pengeluaranBulanan = DB::table('obat_log')
            ->join('obat', 'obat_log.IdObat', '=', 'obat.IdObat')
            ->select(
                DB::raw('MONTH(obat_log.Tanggal) as bulan'),
                DB::raw('SUM(obat_log.Jumlah * obat.HargaBeli) as total')
            )
            ->where('obat_log.Aksi', 'MASUK')
            ->whereYear('obat_log.Tanggal', $year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Detail transaksi
        $detailPendapatan = Pembayaran::with(['rekamMedis.pasien', 'rekamMedis.dokter'])
            ->where('Status', 'PAID')
            ->whereYear('TanggalPembayaran', $year)
            ->when($month, fn($q) => $q->whereMonth('TanggalPembayaran', $month))
            ->orderBy('TanggalPembayaran', 'desc')
            ->limit(50)
            ->get();

        $labaKotor = $pendapatanKotor - $pengeluaran;

        return view('admin.laporan.keuangan', compact(
            'year',
            'month',
            'pendapatanKotor',
            'pengeluaran',
            'labaKotor',
            'pendapatanBulanan',
            'pengeluaranBulanan',
            'detailPendapatan'
        ));
    }

    /**
     * Laporan Pembelian Obat (dari obat_log)
     */
    public function pembelianObat(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', null);

        $query = DB::table('obat_log')
            ->join('obat', 'obat_log.IdObat', '=', 'obat.IdObat')
            ->where('obat_log.Aksi', 'MASUK')
            ->whereYear('obat_log.Tanggal', $year);

        if ($month) {
            $query->whereMonth('obat_log.Tanggal', $month);
        }

        $pembelian = $query->select(
            'obat_log.*',
            'obat.NamaObat',
            'obat.Satuan',
            'obat.HargaBeli',
            DB::raw('(obat_log.Jumlah * obat.HargaBeli) as Subtotal')
        )
            ->orderBy('obat_log.Tanggal', 'desc')
            ->get();

        $totalPembelian = $pembelian->sum('Subtotal');

        // Summary per bulan
        $pembelianBulanan = DB::table('obat_log')
            ->join('obat', 'obat_log.IdObat', '=', 'obat.IdObat')
            ->select(
                DB::raw('MONTH(obat_log.Tanggal) as bulan'),
                DB::raw('SUM(obat_log.Jumlah * obat.HargaBeli) as total'),
                DB::raw('SUM(obat_log.Jumlah) as total_item')
            )
            ->where('obat_log.Aksi', 'MASUK')
            ->whereYear('obat_log.Tanggal', $year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('admin.laporan.pembelian-obat', compact(
            'year',
            'month',
            'pembelian',
            'totalPembelian',
            'pembelianBulanan'
        ));
    }

    /**
     * Laporan Penjualan Obat (dari rekammedis_obat via pembayaran)
     */
    public function penjualanObat(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', null);

        $query = DB::table('rekammedis_obat')
            ->join('rekammedis', 'rekammedis_obat.IdRekamMedis', '=', 'rekammedis.IdRekamMedis')
            ->join('obat', 'rekammedis_obat.IdObat', '=', 'obat.IdObat')
            ->join('pembayaran', 'rekammedis.IdRekamMedis', '=', 'pembayaran.IdRekamMedis')
            ->where('pembayaran.Status', 'PAID')
            ->whereYear('pembayaran.TanggalPembayaran', $year);

        if ($month) {
            $query->whereMonth('pembayaran.TanggalPembayaran', $month);
        }

        $penjualan = $query->select(
            'obat.NamaObat',
            'obat.Satuan',
            DB::raw('SUM(rekammedis_obat.Jumlah) as TotalJumlah'),
            DB::raw('SUM(rekammedis_obat.Jumlah * rekammedis_obat.HargaSatuan) as TotalPenjualan')
        )
            ->groupBy('obat.IdObat', 'obat.NamaObat', 'obat.Satuan')
            ->orderBy('TotalPenjualan', 'desc')
            ->get();

        $totalPenjualan = $penjualan->sum('TotalPenjualan');

        // Penjualan per bulan
        $penjualanBulanan = DB::table('rekammedis_obat')
            ->join('rekammedis', 'rekammedis_obat.IdRekamMedis', '=', 'rekammedis.IdRekamMedis')
            ->join('pembayaran', 'rekammedis.IdRekamMedis', '=', 'pembayaran.IdRekamMedis')
            ->select(
                DB::raw('MONTH(pembayaran.TanggalPembayaran) as bulan'),
                DB::raw('SUM(rekammedis_obat.Jumlah * rekammedis_obat.HargaSatuan) as total')
            )
            ->where('pembayaran.Status', 'PAID')
            ->whereYear('pembayaran.TanggalPembayaran', $year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('admin.laporan.penjualan-obat', compact(
            'year',
            'month',
            'penjualan',
            'totalPenjualan',
            'penjualanBulanan'
        ));
    }

    /**
     * Laporan Pemakaian Obat
     */
    public function pemakaianObat(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', null);

        $query = DB::table('rekammedis_obat')
            ->join('rekammedis', 'rekammedis_obat.IdRekamMedis', '=', 'rekammedis.IdRekamMedis')
            ->join('obat', 'rekammedis_obat.IdObat', '=', 'obat.IdObat')
            ->whereYear('rekammedis.Tanggal', $year);

        if ($month) {
            $query->whereMonth('rekammedis.Tanggal', $month);
        }

        $pemakaian = $query->select(
            'obat.IdObat',
            'obat.NamaObat',
            'obat.Satuan',
            'obat.Stok as StokSekarang',
            DB::raw('SUM(rekammedis_obat.Jumlah) as TotalPemakaian')
        )
            ->groupBy('obat.IdObat', 'obat.NamaObat', 'obat.Satuan', 'obat.Stok')
            ->orderBy('TotalPemakaian', 'desc')
            ->get();

        // Pemakaian per bulan
        $pemakaianBulanan = DB::table('rekammedis_obat')
            ->join('rekammedis', 'rekammedis_obat.IdRekamMedis', '=', 'rekammedis.IdRekamMedis')
            ->select(
                DB::raw('MONTH(rekammedis.Tanggal) as bulan'),
                DB::raw('SUM(rekammedis_obat.Jumlah) as total')
            )
            ->whereYear('rekammedis.Tanggal', $year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('admin.laporan.pemakaian-obat', compact(
            'year',
            'month',
            'pemakaian',
            'pemakaianBulanan'
        ));
    }

    /**
     * Laporan Pendapatan per Obat
     */
    public function pendapatanObat(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $idObat = $request->query('id_obat', null);

        $obats = Obat::orderBy('NamaObat')->get();

        $query = DB::table('rekammedis_obat')
            ->join('rekammedis', 'rekammedis_obat.IdRekamMedis', '=', 'rekammedis.IdRekamMedis')
            ->join('pembayaran', 'rekammedis.IdRekamMedis', '=', 'pembayaran.IdRekamMedis')
            ->join('obat', 'rekammedis_obat.IdObat', '=', 'obat.IdObat')
            ->where('pembayaran.Status', 'PAID')
            ->whereYear('rekammedis.Tanggal', $year);

        if ($idObat) {
            $query->where('rekammedis_obat.IdObat', $idObat);
        }

        $detailPendapatan = (clone $query)->select(
            'rekammedis.IdRekamMedis',
            'rekammedis.Tanggal',
            'obat.NamaObat',
            'rekammedis_obat.Jumlah',
            'rekammedis_obat.HargaSatuan',
            DB::raw('(rekammedis_obat.Jumlah * rekammedis_obat.HargaSatuan) as Subtotal')
        )
            ->orderBy('rekammedis.Tanggal', 'desc')
            ->get();

        $pendapatanBulanan = $query->select(
            DB::raw('MONTH(rekammedis.Tanggal) as bulan'),
            DB::raw('SUM(rekammedis_obat.Jumlah * rekammedis_obat.HargaSatuan) as total')
        )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->pluck('total', 'bulan')
            ->all();

        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenue[$i] = $pendapatanBulanan[$i] ?? 0;
        }

        return view('admin.laporan.pendapatan-obat', compact(
            'year',
            'idObat',
            'obats',
            'detailPendapatan',
            'monthlyRevenue'
        ));
    }

    /**
     * Laporan Penjualan Tindakan
     */
    public function penjualanTindakan(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', null);

        $query = DB::table('rekammedis_tindakan')
            ->join('rekammedis', 'rekammedis_tindakan.IdRekamMedis', '=', 'rekammedis.IdRekamMedis')
            ->join('tindakan', 'rekammedis_tindakan.IdTindakan', '=', 'tindakan.IdTindakan')
            ->join('pembayaran', 'rekammedis.IdRekamMedis', '=', 'pembayaran.IdRekamMedis')
            ->where('pembayaran.Status', 'PAID')
            ->whereYear('pembayaran.TanggalPembayaran', $year);

        if ($month) {
            $query->whereMonth('pembayaran.TanggalPembayaran', $month);
        }

        $penjualan = $query->select(
            'tindakan.NamaTindakan',
            'tindakan.Kategori',
            DB::raw('COUNT(*) as JumlahTindakan'),
            DB::raw('SUM(rekammedis_tindakan.Harga) as TotalPendapatan')
        )
            ->groupBy('tindakan.IdTindakan', 'tindakan.NamaTindakan', 'tindakan.Kategori')
            ->orderBy('TotalPendapatan', 'desc')
            ->get();

        $totalPendapatan = $penjualan->sum('TotalPendapatan');

        // Per bulan
        $penjualanBulanan = DB::table('rekammedis_tindakan')
            ->join('rekammedis', 'rekammedis_tindakan.IdRekamMedis', '=', 'rekammedis.IdRekamMedis')
            ->join('pembayaran', 'rekammedis.IdRekamMedis', '=', 'pembayaran.IdRekamMedis')
            ->select(
                DB::raw('MONTH(pembayaran.TanggalPembayaran) as bulan'),
                DB::raw('SUM(rekammedis_tindakan.Harga) as total'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->where('pembayaran.Status', 'PAID')
            ->whereYear('pembayaran.TanggalPembayaran', $year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('admin.laporan.penjualan-tindakan', compact(
            'year',
            'month',
            'penjualan',
            'totalPendapatan',
            'penjualanBulanan'
        ));
    }

    /**
     * Download PDF Laporan Keuangan
     */
    public function downloadKeuanganPDF(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', null);

        // Pendapatan
        $pendapatanQuery = Pembayaran::where('Status', 'PAID')
            ->whereYear('TanggalPembayaran', $year);
        if ($month)
            $pendapatanQuery->whereMonth('TanggalPembayaran', $month);
        $pendapatanKotor = $pendapatanQuery->sum('TotalBayar');

        // Pendapatan per bulan
        $pendapatanBulanan = Pembayaran::select(
            DB::raw('MONTH(TanggalPembayaran) as bulan'),
            DB::raw('SUM(TotalBayar) as total')
        )
            ->where('Status', 'PAID')
            ->whereYear('TanggalPembayaran', $year)
            ->when($month, fn($q) => $q->whereMonth('TanggalPembayaran', $month))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Pengeluaran
        $pengeluaranQuery = DB::table('obat_log')
            ->join('obat', 'obat_log.IdObat', '=', 'obat.IdObat')
            ->where('obat_log.Aksi', 'MASUK')
            ->whereYear('obat_log.Tanggal', $year);
        if ($month)
            $pengeluaranQuery->whereMonth('obat_log.Tanggal', $month);
        $pengeluaran = $pengeluaranQuery->select(DB::raw('SUM(obat_log.Jumlah * obat.HargaBeli) as total'))->first()->total ?? 0;

        $labaKotor = $pendapatanKotor - $pengeluaran;

        $pdf = Pdf::loadView('admin.laporan.pdf.keuangan', compact(
            'year',
            'month',
            'pendapatanKotor',
            'pengeluaran',
            'labaKotor',
            'pendapatanBulanan'
        ));

        $filename = $month ? "Laporan-Keuangan-{$year}-{$month}.pdf" : "Laporan-Keuangan-{$year}.pdf";
        return $pdf->download($filename);
    }

    /**
     * Download PDF Laporan Pembelian Obat
     */
    public function downloadPembelianObatPDF(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', null);

        $query = DB::table('obat_log')
            ->join('obat', 'obat_log.IdObat', '=', 'obat.IdObat')
            ->where('obat_log.Aksi', 'MASUK')
            ->whereYear('obat_log.Tanggal', $year);

        if ($month) {
            $query->whereMonth('obat_log.Tanggal', $month);
        }

        $pembelian = $query->select(
            'obat_log.*',
            'obat.NamaObat',
            'obat.Satuan',
            'obat.HargaBeli',
            DB::raw('(obat_log.Jumlah * obat.HargaBeli) as Subtotal')
        )
            ->orderBy('obat_log.Tanggal', 'desc')
            ->get();

        $totalPembelian = $pembelian->sum('Subtotal');

        $pdf = Pdf::loadView('admin.laporan.pdf.pembelian-obat', compact(
            'year',
            'month',
            'pembelian',
            'totalPembelian'
        ));

        $filename = $month ? "Laporan-Pembelian-Obat-{$year}-{$month}.pdf" : "Laporan-Pembelian-Obat-{$year}.pdf";
        return $pdf->download($filename);
    }

    /**
     * Download PDF Laporan Penjualan Obat
     */
    public function downloadPenjualanObatPDF(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', null);

        $query = DB::table('rekammedis_obat')
            ->join('rekammedis', 'rekammedis_obat.IdRekamMedis', '=', 'rekammedis.IdRekamMedis')
            ->join('obat', 'rekammedis_obat.IdObat', '=', 'obat.IdObat')
            ->join('pembayaran', 'rekammedis.IdRekamMedis', '=', 'pembayaran.IdRekamMedis')
            ->where('pembayaran.Status', 'PAID')
            ->whereYear('pembayaran.TanggalPembayaran', $year);

        if ($month) {
            $query->whereMonth('pembayaran.TanggalPembayaran', $month);
        }

        $penjualan = $query->select(
            'obat.NamaObat',
            'obat.Satuan',
            DB::raw('SUM(rekammedis_obat.Jumlah) as TotalJumlah'),
            DB::raw('SUM(rekammedis_obat.Jumlah * rekammedis_obat.HargaSatuan) as TotalPenjualan')
        )
            ->groupBy('obat.IdObat', 'obat.NamaObat', 'obat.Satuan')
            ->orderBy('TotalPenjualan', 'desc')
            ->get();

        $totalPenjualan = $penjualan->sum('TotalPenjualan');

        $pdf = Pdf::loadView('admin.laporan.pdf.penjualan-obat', compact(
            'year',
            'month',
            'penjualan',
            'totalPenjualan'
        ));

        $filename = $month ? "Laporan-Penjualan-Obat-{$year}-{$month}.pdf" : "Laporan-Penjualan-Obat-{$year}.pdf";
        return $pdf->download($filename);
    }

    /**
     * Download PDF Laporan Penjualan Tindakan
     */
    public function downloadPenjualanTindakanPDF(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', null);

        $query = DB::table('rekammedis_tindakan')
            ->join('rekammedis', 'rekammedis_tindakan.IdRekamMedis', '=', 'rekammedis.IdRekamMedis')
            ->join('tindakan', 'rekammedis_tindakan.IdTindakan', '=', 'tindakan.IdTindakan')
            ->join('pembayaran', 'rekammedis.IdRekamMedis', '=', 'pembayaran.IdRekamMedis')
            ->where('pembayaran.Status', 'PAID')
            ->whereYear('pembayaran.TanggalPembayaran', $year);

        if ($month) {
            $query->whereMonth('pembayaran.TanggalPembayaran', $month);
        }

        $penjualan = $query->select(
            'tindakan.NamaTindakan',
            'tindakan.Kategori',
            DB::raw('COUNT(*) as JumlahTindakan'),
            DB::raw('SUM(rekammedis_tindakan.Harga) as TotalPendapatan')
        )
            ->groupBy('tindakan.IdTindakan', 'tindakan.NamaTindakan', 'tindakan.Kategori')
            ->orderBy('TotalPendapatan', 'desc')
            ->get();

        $totalPendapatan = $penjualan->sum('TotalPendapatan');

        $pdf = Pdf::loadView('admin.laporan.pdf.penjualan-tindakan', compact(
            'year',
            'month',
            'penjualan',
            'totalPendapatan'
        ));

        $filename = $month ? "Laporan-Penjualan-Tindakan-{$year}-{$month}.pdf" : "Laporan-Penjualan-Tindakan-{$year}.pdf";
        return $pdf->download($filename);
    }

    public function downloadPDF(Request $request)
    {
        $year = $request->query('year', date('Y'));

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
        $totalPasienNew = Pasien::whereHas('user', function ($q) use ($year) {
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

        $medicineCost = DB::table('obat_log')
            ->join('obat', 'obat_log.IdObat', '=', 'obat.IdObat')
            ->where('obat_log.Aksi', 'MASUK')
            ->whereYear('obat_log.Tanggal', $year)
            ->select(DB::raw('SUM(obat_log.Jumlah * obat.HargaBeli) as total_cost'))
            ->first()
            ->total_cost ?? 0;

        $estimatedProfit = $totalRevenueYear - $medicineCost;

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'revenueData',
            'totalRevenueYear',
            'totalPasienNew',
            'totalPemeriksaan',
            'popularTindakan',
            'medicineCost',
            'estimatedProfit',
            'year'
        ));

        return $pdf->download("Laporan-Tahunan-{$year}.pdf");
    }
}
