<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['jadwal.dokter', 'pasien']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->byStatus($request->status);
        }

        // Filter by pasien
        if ($request->has('pasien') && $request->pasien != '') {
            $query->byPasien($request->pasien);
        }

        // Filter by tanggal
        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->whereHas('jadwal', function ($q) use ($request) {
                $q->whereDate('Tanggal', $request->tanggal);
            });
        }

        // Search by booking ID or pasien name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('IdBooking', 'like', "%{$search}%")
                  ->orWhereHas('pasien', function ($q2) use ($search) {
                      $q2->where('Nama', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->orderBy('TanggalBooking', 'desc')->paginate(15);

        // Get all pasien for filter
        $pasiens = Pasien::orderBy('Nama')->get();

        return view('admin.booking.index', compact('bookings', 'pasiens'));
    }

    public function create()
    {
        // Auto Update status jadwals yang sudah lewat
        \App\Models\Jadwal::autoUpdateStatus();

        // Get all pasien
        $pasiens = Pasien::orderBy('Nama')->get();

        // Get available jadwal (future dates only)
        $jadwals = Jadwal::with('dokter')
            ->available()
            ->orderBy('Tanggal')
            ->orderBy('JamMulai')
            ->get();

        return view('admin.booking.create', compact('pasiens', 'jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'PasienID' => 'required|exists:pasien,PasienID',
            'IdJadwal' => 'required|exists:jadwal,IdJadwal',
            'Status' => 'nullable|in:PRESENT,CANCELLED'
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

            $tanggalBooking = now();

            // Call stored procedure Sp_InsertBooking
            DB::statement('CALL Sp_InsertBooking(?, ?, ?, ?, @new_booking_id)', [
                $request->IdJadwal,
                $request->PasienID,
                $tanggalBooking,
                $request->Status ?? 'PRESENT'
            ]);

            // Get the new IdBooking
            $result = DB::select('SELECT @new_booking_id as new_booking_id');
            $newIdBooking = $result[0]->new_booking_id ?? null;

            \Log::info('Booking berhasil dibuat', ['IdBooking' => $newIdBooking]);

            return redirect()->route('admin.booking')->with('success', 'Booking berhasil ditambahkan');

        } catch (\Exception $e) {
            \Log::error('Gagal menambahkan booking', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan booking: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $booking = Booking::with(['jadwal.dokter', 'pasien'])->findOrFail($id);
        return view('admin.booking.edit', compact('booking'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Status' => 'required|in:PRESENT,CANCELLED'
        ]);

        try {
            $booking = Booking::findOrFail($id);
            $booking->update(['Status' => $request->Status]);

            \Log::info('Booking berhasil diupdate', ['IdBooking' => $id]);

            return redirect()->route('admin.booking')->with('success', 'Booking berhasil diupdate');

        } catch (\Exception $e) {
            \Log::error('Gagal mengupdate booking', [
                'error' => $e->getMessage(),
                'IdBooking' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate booking: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Get booking details before/after SP for email
            $booking = Booking::with(['pasien.user', 'jadwal.dokter'])->findOrFail($id);

            // Call stored procedure Sp_CancelBooking
            DB::statement('CALL Sp_CancelBooking(?)', [$id]);

            // Send Email to patient if user exists
            if ($booking->pasien && $booking->pasien->user && $booking->pasien->user->email) {
                try {
                    \Illuminate\Support\Facades\Mail::to($booking->pasien->user->email)->send(new \App\Mail\BookingCancelled($booking));
                } catch (\Exception $e) {
                    \Log::error('Gagal mengirim email pembatalan: ' . $e->getMessage());
                }
            }

            \Log::info('Booking berhasil dibatalkan', ['IdBooking' => $id]);

            return redirect()->route('admin.booking')->with('success', 'Booking berhasil dibatalkan dan email pemberitahuan telah dikirim.');

        } catch (\Exception $e) {
            \Log::error('Gagal membatalkan booking', [
                'error' => $e->getMessage(),
                'IdBooking' => $id
            ]);

            return redirect()->back()->with('error', 'Gagal membatalkan booking: ' . $e->getMessage());
        }
    }
}
