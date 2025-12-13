<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasienController extends Controller
{
    public function index()
    {
        $pasien = Pasien::orderBy('created_at', 'desc')->paginate(10);
        return view('pasien.index', compact('pasien'));
    }

    public function create()
    {
        return view('pasien.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama' => 'required',
            'TanggalLahir' => 'required|date',
            'Alamat' => 'required',
            'NoTelp' => 'required',
            'email' => 'required|email|unique:Pasien,email',
            'JenisKelamin' => 'required|in:L,P',
        ]);

        // Generate PasienID
        $tahun = date('Y');
        $lastPasien = DB::table('Pasien')
            ->where('PasienID', 'like', "P-{$tahun}-%")
            ->orderBy('PasienID', 'desc')
            ->first();

        if ($lastPasien) {
            $lastSeq = (int) substr($lastPasien->PasienID, -5);
            $newSeq = $lastSeq + 1;
        } else {
            $newSeq = 1;
        }

        $pasienId = sprintf('P-%s-%05d', $tahun, $newSeq);

        // Insert data pasien
        Pasien::create([
            'PasienID' => $pasienId,
            'Nama' => $request->Nama,
            'TanggalLahir' => $request->TanggalLahir,
            'Alamat' => $request->Alamat,
            'NoTelp' => $request->NoTelp,
            'email' => $request->email,
            'JenisKelamin' => $request->JenisKelamin,
        ]);

        return redirect()->route('pasien.index')->with('success', 'Pasien berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('pasien.show', compact('pasien'));
    }

    // Menampilkan form edit pasien
    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('pasien.edit', compact('pasien'));
    }

    // Mengupdate data pasien
    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama' => 'required',
            'TanggalLahir' => 'required|date',
            'Alamat' => 'required',
            'NoTelp' => 'required',
            'email' => 'required|email|unique:Pasien,email,' . $id . ',PasienID',
            'JenisKelamin' => 'required|in:L,P',
        ]);
        
        $pasien = Pasien::findOrFail($id);
        $pasien->update($request->all());
        
        return redirect()->route('pasien.index')
            ->with('success', 'Pasien berhasil diperbarui.');
    }

    // Menghapus pasien
    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);
        $pasien->delete();
        
        return redirect()->route('pasien.index')
            ->with('success', 'Pasien berhasil dihapus.');
    }
}