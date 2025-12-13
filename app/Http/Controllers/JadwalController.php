<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal = DB::table('Jadwal')
            ->join('Pegawai', 'Jadwal.IdDokter', '=', 'Pegawai.PegawaiID')
            ->where('Jadwal.Status', 'Available')
            ->where('Jadwal.Tanggal', '>=', now()->format('Y-m-d'))
            ->select('Jadwal.*', 'Pegawai.Nama as nama_dokter')
            ->orderBy('Jadwal.Tanggal')
            ->orderBy('Jadwal.JamMulai')
            ->paginate(10);
        
        return view('jadwal.index', compact('jadwal'));
    }
}