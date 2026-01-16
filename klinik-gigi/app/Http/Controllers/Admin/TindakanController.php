<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tindakan;
use Illuminate\Http\Request;

class TindakanController extends Controller
{
    public function index(Request $request)
    {
        $query = Tindakan::query();

        // Search by name
        if ($request->filled('search')) {
            $query->where('NamaTindakan', 'like', '%' . $request->search . '%')
                ->orWhere('IdTindakan', 'like', '%' . $request->search . '%');
        }

        // Filter by role/type
        if ($request->filled('role')) {
            $role = $request->role;
            if ($role === 'dokter_gigi') {
                $query->whereIn('Kategori', ['Gigi Umum', 'Pedodonti']);
            } elseif ($role === 'dokter_spesialis') {
                $query->whereNotIn('Kategori', ['Gigi Umum', 'Pedodonti']);
            }
        }

        // Filter by category
        if ($request->filled('kategori')) {
            $query->where('Kategori', $request->kategori);
        }

        $tindakans = $query->orderBy('IdTindakan', 'asc')->paginate(10);
        $kategoris = Tindakan::distinct()->pluck('Kategori');

        return view('admin.tindakan.index', compact('tindakans', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Tindakan::distinct()->pluck('Kategori');

        // Generate IdTindakan T-xxx
        $lastTindakan = Tindakan::orderBy('IdTindakan', 'desc')->first();
        if ($lastTindakan) {
            $lastNum = (int) substr($lastTindakan->IdTindakan, 2);
            $newId = 'T-' . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newId = 'T-001';
        }

        return view('admin.tindakan.create', compact('kategoris', 'newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'IdTindakan' => 'required|unique:tindakan,IdTindakan',
            'NamaTindakan' => 'required|string|max:100',
            'Kategori' => 'nullable|string|max:50',
            'Harga' => 'required|numeric|min:0',
            'Durasi' => 'nullable'
        ]);

        Tindakan::create($request->all());

        return redirect()->route('admin.tindakan.index')->with('success', 'Tindakan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $tindakan = Tindakan::findOrFail($id);
        $kategoris = Tindakan::distinct()->pluck('Kategori');
        return view('admin.tindakan.edit', compact('tindakan', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $tindakan = Tindakan::findOrFail($id);

        $request->validate([
            'NamaTindakan' => 'required|string|max:100',
            'Kategori' => 'nullable|string|max:50',
            'Harga' => 'required|numeric|min:0',
            'Durasi' => 'nullable'
        ]);

        $tindakan->update($request->all());

        return redirect()->route('admin.tindakan.index')->with('success', 'Tindakan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tindakan = Tindakan::findOrFail($id);
        $tindakan->delete();

        return redirect()->route('admin.tindakan.index')->with('success', 'Tindakan berhasil dihapus.');
    }
}
