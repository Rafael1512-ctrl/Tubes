<?php

namespace App\Http\Controllers;

use App\Models\TindakanSpesialis;
use App\Models\SesiTindakan;
use App\Models\Pasien;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TindakanSpesialisController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = TindakanSpesialis::with(['pasien', 'dokter', 'sessions']);

        // Filter based on role
        if ($user->isDokter()) {
            $dokter = $user->pegawai;
            if ($dokter) {
                $query->where('DokterID', $dokter->PegawaiID);
            }
        } elseif ($user->isPasien()) {
            $pasien = $user->pasien;
            if ($pasien) {
                $query->where('PasienID', $pasien->PasienID);
            }
        }

        $tindakanList = $query->latest()->paginate(15);

        return view('tindakan-spesialis.index', compact('tindakanList'));
    }

    public function create()
    {
        // Only dokter and admin can create
        if (!auth()->user()->isDokter() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $pasiens = Pasien::all();
        $dokters = Pegawai::where('Jabatan', 'Dokter')->get();

        return view('tindakan-spesialis.create', compact('pasiens', 'dokters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'PasienID' => 'required|exists:pasiens,PasienID',
            'DokterID' => 'required|exists:pegawais,PegawaiID',
            'NamaTindakan' => 'required|string|max:200',
            'frequency' => 'required|in:weekly,monthly,custom',
            'custom_days' => 'nullable|integer|min:1',
            'total_sessions' => 'required|integer|min:1|max:52',
            'start_date' => 'required|date',
            'plan_goal' => 'nullable|string',
        ]);

        // Generate ID
        $validated['IdTindakanSpesialis'] = 'TS' . date('Ymd') . Str::random(6);
        $validated['is_periodic'] = true;
        $validated['status'] = 'active';
        $validated['completed_sessions'] = 0;

        $tindakan = TindakanSpesialis::create($validated);

        // Generate sessions automatically
        $tindakan->generateSessions();

        // Generate reminders for each session
        foreach ($tindakan->sessions as $session) {
            $session->generateReminders();
        }

        return redirect()->route('tindakan-spesialis.show', $tindakan->IdTindakanSpesialis)
                         ->with('success', 'Kontrol berkala berhasil dibuat dan jadwal otomatis telah di-generate!');
    }

    public function show($id)
    {
        $tindakan = TindakanSpesialis::with(['pasien', 'dokter', 'sessions.reminders'])
                                     ->findOrFail($id);

        // Check authorization
        $user = auth()->user();
        if ($user->isPasien() && $user->pasien->PasienID != $tindakan->PasienID) {
            abort(403, 'Unauthorized');
        }
        if ($user->isDokter() && $user->pegawai->PegawaiID != $tindakan->DokterID) {
            abort(403, 'Unauthorized');
        }

        return view('tindakan-spesialis.show', compact('tindakan'));
    }

    public function updateSession(Request $request, $sesiId)
    {
        $session = SesiTindakan::findOrFail($sesiId);
        
        $validated = $request->validate([
            'status' => 'required|in:attended,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'attended' || $validated['status'] === 'completed') {
            $session->markAsAttended($validated['notes'] ?? null);
        } elseif ($validated['status'] === 'cancelled') {
            $session->cancel($validated['notes'] ?? 'Cancelled by staff');
        }

        return redirect()->back()->with('success', 'Status sesi berhasil diupdate!');
    }

    public function rescheduleSession(Request $request, $sesiId)
    {
        $session = SesiTindakan::findOrFail($sesiId);
        
        $validated = $request->validate([
            'new_date' => 'required|date|after:today',
            'reason' => 'required|string|max:200',
        ]);

        $session->reschedule($validated['new_date'], $validated['reason']);

        return redirect()->back()->with('success', 'Sesi berhasil dijadwalkan ulang!');
    }

    public function cancelSession(Request $request, $sesiId)
    {
        $session = SesiTindakan::findOrFail($sesiId);
        
        $validated = $request->validate([
            'reason' => 'required|string|max:200',
        ]);

        $session->cancel($validated['reason']);

        return redirect()->back()->with('success', 'Sesi berhasil dibatalkan!');
    }
}
