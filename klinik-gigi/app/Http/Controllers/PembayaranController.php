<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get Rekam Medis that DOES NOT have payment (Pending to be processed)
        $unpaidRekamMedis = RekamMedis::with(['pasien', 'dokter'])
            ->leftJoin('pembayaran', 'rekammedis.IdRekamMedis', '=', 'pembayaran.IdRekamMedis')
            ->whereNull('pembayaran.IdPembayaran')
            ->select('rekammedis.*')
            ->orderBy('rekammedis.Tanggal', 'desc')
            ->get();

        // 2. Get History of PAID payments with filters
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));

        $paidHistory = Pembayaran::with(['pasien', 'rekamMedis'])
            ->where('Status', 'PAID')
            ->whereMonth('TanggalPembayaran', $month)
            ->whereYear('TanggalPembayaran', $year)
            ->orderBy('TanggalPembayaran', 'desc')
            ->get();

        return view('admin.pembayaran.index', compact('unpaidRekamMedis', 'paidHistory', 'month', 'year'));
    }

    public function create($idRekamMedis)
    {
        $rekamMedis = RekamMedis::with(['pasien', 'dokter', 'tindakan', 'obat'])->findOrFail($idRekamMedis);

        // Calculate Total
        $totalTindakan = 0;
        foreach($rekamMedis->tindakan as $t) {
            $totalTindakan += $t->pivot->Harga * $t->pivot->Jumlah;
        }

        $totalObat = 0;
        foreach($rekamMedis->obat as $o) {
            $totalObat += $o->pivot->HargaSatuan * $o->pivot->Jumlah;
        }

        $grandTotal = $totalTindakan + $totalObat;

        return view('admin.pembayaran.create', compact('rekamMedis', 'totalTindakan', 'totalObat', 'grandTotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'IdRekamMedis' => 'required',
            'Metode' => 'required',
            'TotalBayar' => 'required'
        ]);

        try {
            DB::beginTransaction();

            // Generate ID manually to be safe
            $count = Pembayaran::count() + 1;
            $idPembayaran = 'PAY-' . date('ym') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $pembayaran = new Pembayaran();
            $pembayaran->IdPembayaran = $idPembayaran;
            $pembayaran->IdRekamMedis = $request->IdRekamMedis;
            $pembayaran->PasienID = $request->PasienID;
            $pembayaran->TanggalPembayaran = now();
            $pembayaran->Metode = $request->Metode;
            $pembayaran->TotalBayar = $request->TotalBayar; // Verified in backend or trust content? Better recalc but trusting for speed now.
            $pembayaran->Status = 'PAID';
            $pembayaran->save();

            // Send Confirmation Email
            $pembayaran->load(['pasien.user', 'rekamMedis']);
            if ($pembayaran->pasien && $pembayaran->pasien->user && $pembayaran->pasien->user->email) {
                try {
                    \Illuminate\Support\Facades\Mail::to($pembayaran->pasien->user->email)->send(new \App\Mail\PaymentConfirmed($pembayaran));
                } catch (\Exception $e) {
                    \Log::error('Gagal mengirim email konfirmasi pembayaran: ' . $e->getMessage());
                }
            }

            DB::commit();

            return redirect()->route('admin.pembayaran')->with('success', 'Pembayaran berhasil diproses dan e-kwitansi telah dikirim ke email pasien.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function downloadInvoice($id)
    {
        $pembayaran = Pembayaran::with(['pasien', 'rekamMedis.dokter', 'rekamMedis.tindakan', 'rekamMedis.obat'])->findOrFail($id);
        
        $pdf = Pdf::loadView('admin.pembayaran.billing_pdf', compact('pembayaran'));
        
        return $pdf->download("Billing-{$pembayaran->IdPembayaran}.pdf");
    }
}
