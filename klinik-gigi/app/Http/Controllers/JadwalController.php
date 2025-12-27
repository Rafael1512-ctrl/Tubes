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
            $jadwal = Jadwal::findOrFail($id);

            // Update using stored procedure for status
            if ($request->has('Status')) {
                DB::statement('CALL Sp_UpdateJadwalStatus(?, ?)', [
                    $id,
                    $request->Status
                ]);
            }

            // Update kapasitas manually if provided
            if ($request->has('Kapasitas')) {
                $jadwal->update(['Kapasitas' => $request->Kapasitas]);
            }

            \Log::info('Jadwal berhasil diupdate', ['IdJadwal' => $id]);

            return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil diupdate');

        } catch (\Exception $e) {
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
            // Soft delete by updating status to Cancelled
            DB::statement('CALL Sp_UpdateJadwalStatus(?, ?)', [$id, 'Cancelled']);

            \Log::info('Jadwal berhasil dihapus (cancelled)', ['IdJadwal' => $id]);

            return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil dibatalkan');

        } catch (\Exception $e) {
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
