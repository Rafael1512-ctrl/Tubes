<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        // Auto Update status jadwals yang sudah lewat
        \App\Models\Jadwal::autoUpdateStatus();

        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        $dokterId = $request->get('dokter');

        $query = Jadwal::with('dokter')
            ->byBulan($year, $month);

        if ($dokterId) {
            $query->byDokter($dokterId);
        }

        $jadwals = $query->orderBy('Tanggal')->orderBy('JamMulai')->get();

        // Get all dokter for filter
        $dokters = Pegawai::whereIn('Jabatan', ['dokter gigi', 'dokter spesialis'])->get();

        return view('admin.jadwal.index', compact('jadwals', 'dokters', 'year', 'month'));
    }

    public function create()
    {
        // Get all dokter
        $dokters = Pegawai::whereIn('Jabatan', ['dokter gigi', 'dokter spesialis'])->get();
        return view('admin.jadwal.create', compact('dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'IdDokter' => 'required|exists:pegawai,PegawaiID',
            'Tanggal' => 'required|date|after_or_equal:today',
            'Sesi' => 'required|in:pagi,sore',
            'Status' => 'nullable|in:Available,Cancelled'
        ]);

        try {
            // Validasi jam jika tanggal == today (Batas: 1 jam sebelum berakhir)
            if ($request->Tanggal == now()->toDateString()) {
                $currentTime = now()->toTimeString();
                // Pagi berakhir 12:00, batas 11:00
                if ($request->Sesi == 'pagi' && $currentTime >= '11:00:00') {
                    return redirect()->back()->withInput()->with('error', 'Sesi pagi untuk hari ini sudah tidak tersedia (batas 1 jam sebelum berakhir).');
                }
                // Sore berakhir 20:00, batas 19:00
                if ($request->Sesi == 'sore' && $currentTime >= '19:00:00') {
                    return redirect()->back()->withInput()->with('error', 'Sesi sore untuk hari ini sudah tidak tersedia (batas 1 jam sebelum berakhir).');
                }
            }

            // Cek apakah jadwal sudah ada
            $exists = Jadwal::where('IdDokter', $request->IdDokter)
                ->where('Tanggal', $request->Tanggal)
                ->where(function($q) use ($request) {
                    if($request->Sesi == 'pagi') $q->where('JamMulai', '09:00:00');
                    else $q->where('JamMulai', '17:00:00');
                })->exists();
            
            if ($exists) {
                return redirect()->back()->withInput()->with('error', 'Dokter sudah memiliki jadwal di sesi ini.');
            }

            // Call stored procedure Sp_InsertJadwal
            DB::statement('CALL Sp_InsertJadwal(?, ?, ?, ?, @new_id)', [
                $request->IdDokter,
                $request->Tanggal,
                $request->Sesi,
                $request->Status ?? 'Available'
            ]);

            // Get the new IdJadwal
            $result = DB::select('SELECT @new_id as new_jadwal_id');
            $newIdJadwal = $result[0]->new_jadwal_id ?? null;

            \Log::info('Jadwal berhasil dibuat', ['IdJadwal' => $newIdJadwal]);

            return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil ditambahkan');

        } catch (\Exception $e) {
            \Log::error('Gagal menambahkan jadwal', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan jadwal: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $jadwal = Jadwal::with('dokter')->findOrFail($id);
        return view('admin.jadwal.edit', compact('jadwal'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Status' => 'required|in:Available,Full,Cancelled',
            'Kapasitas' => 'nullable|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $jadwal = Jadwal::findOrFail($id);
            $newStatus = $request->Status;

            // 1. Jika status berubah menjadi CANCELLED, batalkan semua booking terkait
            if ($newStatus === 'Cancelled') {
                \App\Models\Booking::where('IdJadwal', $id)
                    ->where('Status', '!=', 'CANCELLED')
                    ->update(['Status' => 'CANCELLED']);
                
                \Log::info('Auto-cancelling bookings for Jadwal', ['IdJadwal' => $id]);
            }

            // 2. Update Status Jadwal
            // Gunakan update Eloquent langsung agar lebih reliable
            // SP: CALL Sp_UpdateJadwalStatus(?, ?)
            $jadwal->update(['Status' => $newStatus]);

            // 3. Update Kapasitas jika ada
            if ($request->has('Kapasitas')) {
                $jadwal->update(['Kapasitas' => $request->Kapasitas]);
            }

            DB::commit();

            \Log::info('Jadwal berhasil diupdate', ['IdJadwal' => $id, 'NewStatus' => $newStatus]);

            return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal mengupdate jadwal', [
                'error' => $e->getMessage(),
                'IdJadwal' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate jadwal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // 1. Batalkan semua booking terkait
            \App\Models\Booking::where('IdJadwal', $id)
                ->where('Status', '!=', 'CANCELLED')
                ->update(['Status' => 'CANCELLED']);

            // 2. Update status jadwal menjadi Cancelled
            DB::statement('CALL Sp_UpdateJadwalStatus(?, ?)', [$id, 'Cancelled']);

            DB::commit();

            \Log::info('Jadwal berhasil dihapus (cancelled)', ['IdJadwal' => $id]);

            return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil dibatalkan dan booking terkait telah dicancel.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal menghapus jadwal', [
                'error' => $e->getMessage(),
                'IdJadwal' => $id
            ]);

            return redirect()->back()->with('error', 'Gagal membatalkan jadwal: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint: Get jadwal by date (for calendar)
     */
    public function getByDate($date)
    {
        $jadwals = Jadwal::with('dokter')
            ->byTanggal($date)
            ->get()
            ->map(function ($jadwal) {
                return [
                    'id' => $jadwal->IdJadwal,
                    'dokter' => $jadwal->dokter->Nama ?? '-',
                    'jam' => $jadwal->formatted_jam,
                    'sesi' => $jadwal->sesi,
                    'status' => $jadwal->Status,
                    'kapasitas' => $jadwal->Kapasitas,
                    'sisa' => $jadwal->sisa_kapasitas,
                    'is_full' => $jadwal->is_full
                ];
            });

        return response()->json($jadwals);
    }
}
