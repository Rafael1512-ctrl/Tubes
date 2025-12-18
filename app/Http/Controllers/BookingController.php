<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = DB::table('bookings')
            ->join('jadwals', 'bookings.IdJadwal', '=', 'jadwals.IdJadwal')
            ->join('pegawais', 'jadwals.IdDokter', '=', 'pegawais.PegawaiID')
            ->join('pasiens', 'bookings.PasienID', '=', 'pasiens.PasienID')
            ->select('bookings.*', 'jadwals.Tanggal', 'jadwals.JamMulai', 'jadwals.JamAkhir', 
                     'pegawais.Nama as nama_dokter', 'pasiens.Nama as nama_pasien')
            ->orderBy('jadwals.Tanggal', 'desc')
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
            // Generate IdBooking: B-YYMM-XXXX
            $prefix = 'B-' . date('ym') . '-';
            $lastBooking = \App\Models\Booking::where('IdBooking', 'like', $prefix . '%')
                            ->orderBy('IdBooking', 'desc')
                            ->first();

            $newSeq = $lastBooking ? ((int) substr($lastBooking->IdBooking, 8)) + 1 : 1;
            $idBooking = $prefix . str_pad($newSeq, 4, '0', STR_PAD_LEFT);

            // Create Booking
            \App\Models\Booking::create([
                'IdBooking' => $idBooking,
                'IdJadwal' => $request->IdJadwal,
                'PasienID' => $request->PasienID,
                'TanggalBooking' => now(), // Default to now
                'Status' => 'PRESENT', // Default
                'Keluhan' => $request->Keluhan ?? '-',
            ]);
            
            return redirect()->route('booking.index')
                ->with('success', 'Booking berhasil dibuat.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}