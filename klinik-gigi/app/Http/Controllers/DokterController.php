<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\Pasien;
use App\Models\RekamMedis;

class DokterController extends Controller
{
    public function jadwal(Request $request)
    {
        $user = Auth::user();
        $dokter = $user->pegawai;

        // Ambil jadwal dokter ini
        $jadwals = Jadwal::withCount(['bookings' => function($q) {
                $q->where('Status', '!=', 'CANCELLED');
            }])
            ->where('IdDokter', $dokter->PegawaiID)
            ->whereDate('Tanggal', '>=', now())
            ->orderBy('Tanggal')
            ->orderBy('JamMulai')
            ->paginate(10);

        return view('dokter.jadwal.index', compact('jadwals', 'dokter'));
    }

    public function pasien(Request $request)
    {
        $search = $request->query('search');

        $pasiens = Pasien::when($search, function($query, $search) {
            return $query->where('Nama', 'LIKE', "%{$search}%")
                         ->orWhere('PasienID', 'LIKE', "%{$search}%");
        })->paginate(15);

        return view('dokter.pasien.index', compact('pasiens'));
    }

    public function history($id)
    {
        $pasien = Pasien::with(['rekamMedis.dokter', 'rekamMedis.tindakan', 'rekamMedis.obat'])->findOrFail($id);
        
        return view('dokter.pasien.history', compact('pasien'));
    }

    public function riwayat(Request $request)
    {
        $user = Auth::user();
        $dokter = $user->pegawai;

        $query = RekamMedis::with(['pasien', 'tindakan', 'obat'])
            ->where('DokterID', $dokter->PegawaiID);

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('Tanggal', $request->tanggal);
        }

        $riwayat = $query->orderBy('Tanggal', 'desc')->paginate(10);

        return view('dokter.riwayat.index', compact('riwayat', 'dokter'));
    }
}
