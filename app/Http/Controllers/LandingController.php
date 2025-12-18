<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pasien;
// use App\Models\Service; // If dynamic services needed

class LandingController extends Controller
{
    public function index()
    {
        return view('landing.home');
    }

    public function booking()
    {
        // Fetch inputs if dynamic (e.g. Services, Doctors)
        return view('landing.booking');
    }

    public function storeBooking(Request $request)
    {
        // Validate
        $request->validate([
            // 'nama' => 'required',
            // 'no_wa' => 'required',
            // ...
        ]);

        // Logic to store booking (complex because it needs Pasien ID, etc.)
        // For prototype, just redirect back with success message
        
        return redirect()->back()->with('success', 'Booking berhasil dikirim! Kami akan menghubungi Anda segera.');
    }
}
