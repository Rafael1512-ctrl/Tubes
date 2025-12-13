<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = DB::table('Booking')
            ->join('Jadwal', 'Booking.IdJadwal', '=', 'Jadwal.IdJadwal')
            ->join('Pegawai', 'Jadwal.IdDokter', '=', 'Pegawai.PegawaiID')
            ->join('Pasien', 'Booking.PasienID', '=', 'Pasien.PasienID')
            ->select('Booking.*', 'Jadwal.Tanggal', 'Jadwal.JamMulai', 'Jadwal.JamAkhir', 
                     'Pegawai.Nama as nama_dokter', 'Pasien.Nama as nama_pasien')
            ->orderBy('Jadwal.Tanggal', 'desc')
            ->paginate(10);
        
        return view('booking.index', compact('bookings'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'IdJadwal' => 'required',
            'PasienID' => 'required',
            'Keluhan' => 'required',
        ]);
        
        try {
            // Panggil stored procedure untuk booking
            DB::select('CALL sp_simple_booking(?, ?, ?)', [
                $request->IdJadwal,
                $request->PasienID,
                $request->Keluhan
            ]);
            
            return redirect()->route('booking.index')
                ->with('success', 'Booking berhasil dibuat.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}