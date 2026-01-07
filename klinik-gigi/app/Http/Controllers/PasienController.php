<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\RekamMedis;
use App\Models\Jadwal;
use Illuminate\Support\Facades\DB;

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

    public function bookingCreate()
    {
        $user = Auth::user();
        $pasien = $user->pasien;
        
        if (!$pasien) {
            return redirect()->route('pasien.dashboard')->with('error', 'Data pasien tidak ditemukan. Silahkan lengkapi profil Anda.');
        }

        // Auto Update status jadwals yang sudah lewat
        Jadwal::autoUpdateStatus();

        // Get available jadwal (future dates only)
        $jadwals = Jadwal::with('dokter')
            ->available()
            ->orderBy('Tanggal')
            ->orderBy('JamMulai')
            ->get();

        return view('pasien.booking.create', compact('pasien', 'jadwals'));
    }

    public function bookingStore(Request $request)
    {
        $user = Auth::user();
        $pasien = $user->pasien;

        if (!$pasien) {
            return redirect()->route('pasien.dashboard')->with('error', 'Data pasien tidak ditemukan.');
        }

        $request->validate([
            'IdJadwal' => 'required|exists:jadwal,IdJadwal',
        ]);

        try {
            // Check if Jadwal is still available and not in the past
            $jadwal = Jadwal::available()->find($request->IdJadwal);
            
            if (!$jadwal) {
                return redirect()->back()->withInput()->with('error', 'Jadwal yang dipilih sudah tidak tersedia atau sudah terlewati.');
            }

            // Check if Jadwal is full
            if ($jadwal->is_full) {
                return redirect()->back()->withInput()->with('error', 'Maaf, kapasitas jadwal ini sudah penuh.');
            }

            // Check if already booked for this schedule
            $existingBooking = Booking::where('PasienID', $pasien->PasienID)
                ->where('IdJadwal', $request->IdJadwal)
                ->where('Status', 'PRESENT')
                ->first();

            if ($existingBooking) {
                return redirect()->back()->withInput()->with('error', 'Anda sudah memiliki booking aktif untuk jadwal ini.');
            }

            $tanggalBooking = now();

            // Call stored procedure Sp_InsertBooking
            DB::statement('CALL Sp_InsertBooking(?, ?, ?, ?, @new_booking_id)', [
                $request->IdJadwal,
                $pasien->PasienID,
                $tanggalBooking,
                'PRESENT'
            ]);

            // Get the new IdBooking
            $result = DB::select('SELECT @new_booking_id as new_booking_id');
            $newIdBooking = $result[0]->new_booking_id ?? null;

            \Log::info('Booking pasien berhasil dibuat', ['IdBooking' => $newIdBooking, 'PasienID' => $pasien->PasienID]);

            return redirect()->route('pasien.jadwal')->with('success', 'Janji temu berhasil dibuat! Silahkan datang tepat waktu.');

        } catch (\Exception $e) {
            \Log::error('Gagal menambahkan booking pasien', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat janji temu: ' . $e->getMessage());
        }
    }
}
