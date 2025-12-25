<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\JenisObat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        // Only admin can manage obat
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $filter = $request->get('filter', 'all');
        
        $query = Obat::with('jenisObat');

        switch ($filter) {
            case 'expired':
                $query->expired();
                break;
            case 'expiring':
                $query->expiringSoon(30);
                break;
            case 'valid':
                $query->unexpired();
                break;
            default:
                // all
                break;
        }

        $obats = $query->orderBy('exp_date', 'asc')->paginate(20);

        return view('obat.index', compact('obats', 'filter'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $jenisObats = JenisObat::all();
        return view('obat.create', compact('jenisObats'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'IdJenisObat' => 'required|exists:jenis_obats,JenisObatID',
            'NamaObat' => 'required|string|max:100',
            'Satuan' => 'nullable|string|max:20',
            'Harga' => 'required|numeric|min:0',
            'Stok' => 'required|integer|min:0',
            'exp_date' => 'required|date|after:today',
        ]);

        // Generate ID
        $validated['IdObat'] = 'OB' . date('Ymd') . Str::random(4);

        Obat::create($validated);

        return redirect()->route('obat.index')->with('success', 'Obat berhasil ditambahkan!');
    }

    public function edit($id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $obat = Obat::findOrFail($id);
        $jenisObats = JenisObat::all();
        
        return view('obat.edit', compact('obat', 'jenisObats'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $obat = Obat::findOrFail($id);

        $validated = $request->validate([
            'IdJenisObat' => 'required|exists:jenis_obats,JenisObatID',
            'NamaObat' => 'required|string|max:100',
            'Satuan' => 'nullable|string|max:20',
            'Harga' => 'required|numeric|min:0',
            'Stok' => 'required|integer|min:0',
            'exp_date' => 'required|date',
        ]);

        $obat->update($validated);

        return redirect()->route('obat.index')->with('success', 'Obat berhasil diupdate!');
    }

    public function destroy($id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $obat = Obat::findOrFail($id);
        $obat->delete();

        return redirect()->route('obat.index')->with('success', 'Obat berhasil dihapus!');
    }

    // API endpoint untuk check expiry
    public function checkExpiry(Request $request)
    {
        $obatId = $request->get('obat_id');
        $obat = Obat::find($obatId);

        if (!$obat) {
            return response()->json(['error' => 'Obat tidak ditemukan'], 404);
        }

        return response()->json([
            'is_expired' => $obat->isExpired(),
            'is_expiring_soon' => $obat->isExpiringSoon(30),
            'exp_date' => $obat->exp_date,
            'status' => $obat->getExpiryStatus(),
        ]);
    }
}
