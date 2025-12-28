<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\JenisObat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $jenis = $request->get('jenis');

        $query = Obat::with('jenisObat');

        if ($search) {
            $query->where('NamaObat', 'like', "%{$search}%")
                  ->orWhere('IdObat', 'like', "%{$search}%");
        }

        if ($jenis) {
            $query->where('IdJenisObat', $jenis);
        }

        $obats = $query->orderBy('NamaObat')->paginate(15);
        $jenisObats = JenisObat::orderBy('NamaJenis')->get();

        return view('admin.obat.index', compact('obats', 'jenisObats'));
    }

    public function create()
    {
        $jenisObats = JenisObat::orderBy('NamaJenis')->get();
        return view('admin.obat.create', compact('jenisObats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'IdJenisObat' => 'required|exists:jenisobat,JenisObatID',
            'NamaObat' => 'required|string|max:100',
            'Satuan' => 'required|string|max:20',
            'Harga' => 'required|numeric|min:0',
            'Stok' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Panggil store procedure atau buat ID manual
            // Kita gunakan logic manual mirip SP di SQL
            $prefix = 'O-';
            $lastObat = Obat::where('IdObat', 'like', $prefix . '%')
                ->orderBy('IdObat', 'desc')
                ->first();
            
            if ($lastObat) {
                $lastNum = (int) substr($lastObat->IdObat, 2);
                $newNum = $lastNum + 1;
            } else {
                $newNum = 1;
            }
            
            $newId = $prefix . str_pad($newNum, 5, '0', STR_PAD_LEFT);

            Obat::create([
                'IdObat' => $newId,
                'IdJenisObat' => $request->IdJenisObat,
                'NamaObat' => $request->NamaObat,
                'Satuan' => $request->Satuan,
                'Harga' => $request->Harga,
                'Stok' => $request->Stok,
            ]);

            DB::commit();
            return redirect()->route('admin.obat')->with('success', 'Obat berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan obat: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $obat = Obat::findOrFail($id);
        $jenisObats = JenisObat::orderBy('NamaJenis')->get();
        return view('admin.obat.edit', compact('obat', 'jenisObats'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'IdJenisObat' => 'required|exists:jenisobat,JenisObatID',
            'NamaObat' => 'required|string|max:100',
            'Satuan' => 'required|string|max:20',
            'Harga' => 'required|numeric|min:0',
            'Stok' => 'required|integer|min:0',
        ]);

        try {
            $obat = Obat::findOrFail($id);
            $obat->update($request->all());

            return redirect()->route('admin.obat')->with('success', 'Obat berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui obat: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $obat = Obat::findOrFail($id);
            $obat->delete();
            return redirect()->route('admin.obat')->with('success', 'Obat berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus obat: ' . $e->getMessage());
        }
    }
}
