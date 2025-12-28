<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Obat;
use App\Models\Tindakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RekamMedisController extends Controller
{
    public function create($idBooking)
    {
        $booking = Booking::with(['pasien', 'jadwal'])->where('IdBooking', $idBooking)->firstOrFail();
        
        // Authorization check (Optional but good: check if logged in doctor is assigned)
        $user = Auth::user();
        $dokter = $user->pegawai;
        if($dokter && $booking->jadwal->IdDokter != $dokter->PegawaiID) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke booking ini.');
        }

        $tindakans = Tindakan::all();
        $obats = Obat::where('Stok', '>', 0)->get();

        return view('dokter.rekam_medis.create', compact('booking', 'tindakans', 'obats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'IdBooking' => 'required|exists:booking,IdBooking',
            'Diagnosa' => 'required|string',
            'tindakan' => 'nullable|array',
            'obat' => 'nullable|array'
        ]);

        try {
            DB::beginTransaction();

            $booking = Booking::with('pasien', 'jadwal')->where('IdBooking', $request->IdBooking)->first();
            $user = Auth::user();
            $dokter = $user->pegawai; // Assuming logged in user is doctor

            // 1. Insert Header Rekam Medis (using SP or Manual)
            // Using logic similar to Sp_InsertRekamMedis_AutoNumber manual for flexibility or calling it.
            // Let's call SP for consistency if it exists and works, or replicate logic safely.
            // Sp_InsertRekamMedis_AutoNumber(p_IdBooking, p_PasienID, p_DokterID, p_Tanggal, p_Diagnosa, p_Catatan, p_CreatedBy)
            
            // Note: The SP implementation in previous context didn't handle "OUT" parameter perfectly via DB::statement in Laravel sometimes.
            // Let's use a simpler approach: Replicate the ID generation and Insert manually for full control, inside transaction.
            
            $year = now()->year;
            $count = \App\Models\RekamMedis::whereYear('Tanggal', $year)->count() + 1;
            $idRekamMedis = 'RM-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            
            $rekamMedis = new \App\Models\RekamMedis();
            $rekamMedis->IdRekamMedis = $idRekamMedis;
            $rekamMedis->IdBooking = $booking->IdBooking;
            $rekamMedis->PasienID = $booking->PasienID;
            $rekamMedis->DokterID = $dokter->PegawaiID;
            $rekamMedis->Tanggal = now();
            $rekamMedis->Diagnosa = $request->Diagnosa;
            $rekamMedis->Catatan = $request->Catatan;
            $rekamMedis->save();

            // 2. Insert Tindakan
            if ($request->has('tindakan')) {
                foreach ($request->tindakan as $tindakanId) {
                    // Logic allows multiple same tindakan? Usually yes for quantity, but simple ID array implies 1 each.
                    // If UI sends array of IDs:
                    $tindakanData = Tindakan::find($tindakanId);
                    if ($tindakanData) {
                        DB::table('rekammedis_tindakan')->insert([
                            'IdRekamMedis' => $idRekamMedis,
                            'IdTindakan' => $tindakanId,
                            'Jumlah' => 1, // Default 1 for now based on simple UI 
                            'Harga' => $tindakanData->Harga
                        ]);
                    }
                }
            }

            // 3. Insert Obat
            if ($request->has('obat')) {
                // Expecting structure: obat[index][id], obat[index][qty], obat[index][dosis]
                foreach ($request->obat as $obatItem) {
                    if (!empty($obatItem['id'])) {
                        $obatData = Obat::find($obatItem['id']);
                        if ($obatData) {
                            DB::table('rekammedis_obat')->insert([
                                'IdRekamMedis' => $idRekamMedis,
                                'IdObat' => $obatItem['id'],
                                'Dosis' => $obatItem['dosis'] ?? '-',
                                'Frekuensi' => $obatItem['frekuensi'] ?? '-',
                                'LamaHari' => $obatItem['days'] ?? 1,
                                'Jumlah' => $obatItem['qty'] ?? 1,
                                'HargaSatuan' => $obatData->Harga
                            ]);

                            // Kurangi Stok (Simple)
                            $obatData->decrement('Stok', $obatItem['qty'] ?? 1);
                        }
                    }
                }
            }

            // 4. Update Booking Status -> COMPLETED
            $booking->update(['Status' => 'COMPLETED']);

            DB::commit();

            return redirect()->route('dokter.dashboard')->with('success', 'Rekam Medis berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan rekam medis: ' . $e->getMessage())->withInput();
        }
    }
}
