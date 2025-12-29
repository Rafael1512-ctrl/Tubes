<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\RekamMedis;

class PasienController extends Controller
{
    public function notifications()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        return view('pasien.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back();
    }

    public function rekamMedis()
    {
        $pasien = Auth::user()->pasien;
        if (!$pasien) return back()->with('error', 'Data pasien tidak ditemukan.');

        $histories = RekamMedis::with(['dokter', 'tindakan', 'obat'])
            ->where('PasienID', $pasien->PasienID)
            ->orderBy('Tanggal', 'desc')
            ->paginate(10);

        return view('pasien.rekammedis.index', compact('histories'));
    }

    public function jadwal()
    {
        $pasien = Auth::user()->pasien;
        if (!$pasien) return back()->with('error', 'Data pasien tidak ditemukan.');

        $bookings = Booking::with(['jadwal.dokter'])
            ->where('PasienID', $pasien->PasienID)
            ->orderBy('TanggalBooking', 'desc')
            ->paginate(10);

        return view('pasien.jadwal.index', compact('bookings'));
    }
}
