<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal = DB::table('jadwals')
            ->join('pegawais', 'jadwals.IdDokter', '=', 'pegawais.PegawaiID')
            ->where('jadwals.Status', 'Available')
            ->where('jadwals.Tanggal', '>=', now()->format('Y-m-d'))
            ->select('jadwals.*', 'pegawais.Nama as nama_dokter')
            ->orderBy('jadwals.Tanggal')
            ->orderBy('jadwals.JamMulai')
            ->paginate(10);
        
        return view('jadwal.index', compact('jadwal'));
    }
}