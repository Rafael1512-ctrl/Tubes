<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    // Menampilkan semua pembayaran
    public function index()
    {
        $pembayaran = Pembayaran::with(['pasien', 'rekamMedis'])
            ->orderBy('TanggalPembayaran', 'desc')
            ->paginate(10);
        return view('pembayaran.index', compact('pembayaran'));
    }
    
    // Menampilkan form pembayaran untuk rekam medis tertentu
    public function create($idRekamMedis = null)
    {
        $rekamMedisList = RekamMedis::whereDoesntHave('pembayaran')
            ->orWhereHas('pembayaran', function($q) {
                $q->where('Status', '!=', 'PAID');
            })
            ->with(['pasien', 'dokter'])
            ->get();
        
        $selectedRM = null;
        if ($idRekamMedis) {
            $selectedRM = RekamMedis::with(['pasien', 'dokter', 'obat', 'tindakan'])->find($idRekamMedis);
        }
        
        return view('pembayaran.create', compact('rekamMedisList', 'selectedRM'));
    }
    
    // Menyimpan pembayaran baru
    public function store(Request $request)
    {
        $request->validate([
            'IdRekamMedis' => 'required',
            'Metode' => 'required',
            'TotalBayar' => 'required|numeric',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Generate ID Pembayaran
            $tahun = date('Y');
            $lastBayar = DB::table('Pembayaran')
                ->where('IdPembayaran', 'like', "BYR-{$tahun}-%")
                ->orderBy('IdPembayaran', 'desc')
                ->first();
            
            if ($lastBayar) {
                $lastSeq = (int) substr($lastBayar->IdPembayaran, -4);
                $newSeq = $lastSeq + 1;
            } else {
                $newSeq = 1;
            }
            
            $bayarId = sprintf('BYR-%s-%04d', $tahun, $newSeq);
            
            // Dapatkan data rekam medis
            $rekamMedis = RekamMedis::with(['pasien'])->find($request->IdRekamMedis);
            
            // Hitung total dari obat dan tindakan
            $totalObat = $rekamMedis->obat->sum(function($obat) {
                return $obat->pivot->Jumlah * $obat->pivot->HargaSatuan;
            });
            
            $totalTindakan = $rekamMedis->tindakan->sum(function($tindakan) {
                return $tindakan->pivot->Jumlah * $tindakan->pivot->Harga;
            });
            
            $totalPerhitungan = $totalObat + $totalTindakan;
            
            // Buat pembayaran
            $pembayaran = Pembayaran::create([
                'IdPembayaran' => $bayarId,
                'IdRekamMedis' => $request->IdRekamMedis,
                'PasienID' => $rekamMedis->PasienID,
                'TanggalPembayaran' => now(),
                'Metode' => $request->Metode,
                'TotalBayar' => $request->TotalBayar,
                'Status' => 'PAID',
                'Keterangan' => $request->Keterangan,
            ]);
            
            DB::commit();
            
            return redirect()->route('pembayaran.index')
                ->with('success', 'Pembayaran berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan pembayaran: ' . $e->getMessage()]);
        }
    }
    
    // Menampilkan detail pembayaran
    public function show($id)
    {
        $pembayaran = Pembayaran::with(['pasien', 'rekamMedis'])->findOrFail($id);
        return view('pembayaran.show', compact('pembayaran'));
    }
}