<?php

namespace App\Http\Controllers;

use App\Models\RekamMedis;
use App\Models\Booking;
use App\Models\Obat;
use App\Models\Tindakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    // Menampilkan semua rekam medis
    public function index()
    {
        $rekamMedis = RekamMedis::with(['pasien', 'dokter'])
            ->orderBy('Tanggal', 'desc')
            ->paginate(10);
        return view('rekam_medis.index', compact('rekamMedis'));
    }
    
    // Menampilkan form tambah rekam medis
    public function create()
    {
        // Ambil booking yang belum memiliki rekam medis
        $bookings = Booking::whereDoesntHave('rekamMedis')
            ->where('Status', 'PRESENT')
            ->with(['pasien', 'jadwal.dokter'])
            ->get();
        
        // Hanya tampilkan obat yang belum expired
        $obat = Obat::unexpired()->get();
        $tindakan = Tindakan::all();
        
        return view('rekam_medis.create', compact('bookings', 'obat', 'tindakan'));
    }
    
    // Menyimpan rekam medis baru
    public function store(Request $request)
    {
        $request->validate([
            'IdBooking' => 'required',
            'Diagnosa' => 'required',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Generate ID Rekam Medis
            $tahun = date('Y');
            $lastRM = DB::table('RekamMedis')
                ->where('IdRekamMedis', 'like', "RM-{$tahun}-%")
                ->orderBy('IdRekamMedis', 'desc')
                ->first();
            
            if ($lastRM) {
                $lastSeq = (int) substr($lastRM->IdRekamMedis, -4);
                $newSeq = $lastSeq + 1;
            } else {
                $newSeq = 1;
            }
            
            $rmId = sprintf('RM-%s-%04d', $tahun, $newSeq);
            
            // Dapatkan data dari booking
            $booking = Booking::with(['jadwal', 'pasien'])->find($request->IdBooking);
            
            // Buat rekam medis
            $rekamMedis = RekamMedis::create([
                'IdRekamMedis' => $rmId,
                'IdBooking' => $request->IdBooking,
                'PasienID' => $booking->PasienID,
                'DokterID' => $booking->jadwal->IdDokter,
                'Tanggal' => now(),
                'Diagnosa' => $request->Diagnosa,
                'Catatan' => $request->Catatan,
                'Anamnesa' => $request->Anamnesa,
                'ResepDokter' => $request->ResepDokter,
            ]);
            
            // Simpan obat yang diresepkan
            if ($request->has('obat')) {
                foreach ($request->obat as $index => $idObat) {
                    if (!empty($idObat)) {
                        $obat = Obat::find($idObat);
                        
                        // Validasi exp_date - obat tidak boleh expired
                        if ($obat->isExpired()) {
                            throw new \Exception("Obat {$obat->NamaObat} sudah melewati tanggal kadaluarsa!");
                        }
                        
                        $rekamMedis->obat()->attach($idObat, [
                            'Dosis' => $request->dosis[$index],
                            'Frekuensi' => $request->frekuensi[$index],
                            'LamaHari' => $request->lama_hari[$index],
                            'Jumlah' => $request->jumlah_obat[$index],
                            'HargaSatuan' => $obat->Harga,
                        ]);
                        
                        // Kurangi stok obat
                        $obat->decrement('Stok', $request->jumlah_obat[$index]);
                    }
                }
            }
            
            // Simpan tindakan
            if ($request->has('tindakan')) {
                foreach ($request->tindakan as $idTindakan) {
                    if (!empty($idTindakan)) {
                        $tindakan = Tindakan::find($idTindakan);
                        $rekamMedis->tindakan()->attach($idTindakan, [
                            'Jumlah' => 1,
                            'Harga' => $tindakan->Harga,
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('rekam-medis.index')
                ->with('success', 'Rekam medis berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan rekam medis: ' . $e->getMessage()]);
        }
    }
    
    // Menampilkan detail rekam medis
    public function show($id)
    {
        $rekamMedis = RekamMedis::with(['pasien', 'dokter', 'obat', 'tindakan', 'tindakanSpesialis'])->findOrFail($id);
        return view('rekam_medis.show', compact('rekamMedis'));
    }
}