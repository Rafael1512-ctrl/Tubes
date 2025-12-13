<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    // Menampilkan semua pegawai (khusus dokter)
    public function index()
    {
        $pegawai = Pegawai::where('Jabatan', 'like', '%Dokter%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('pegawai.index', compact('pegawai'));
    }
    
    // Menampilkan form tambah pegawai (dokter)
    public function create()
    {
        return view('pegawai.create');
    }
    
    // Menyimpan pegawai baru
    public function store(Request $request)
    {
        $request->validate([
            'Nama' => 'required',
            'Jabatan' => 'required',
            'TanggalMasuk' => 'required|date',
            'NoTelp' => 'required',
            'email' => 'required|email|unique:Pegawai,email',
        ]);
        
        // Generate PegawaiID
        $lastPegawai = DB::table('Pegawai')
            ->where('PegawaiID', 'like', 'D-%')
            ->orderBy('PegawaiID', 'desc')
            ->first();
        
        if ($lastPegawai) {
            $lastSeq = (int) substr($lastPegawai->PegawaiID, 2);
            $newSeq = $lastSeq + 1;
        } else {
            $newSeq = 1;
        }
        
        $pegawaiId = sprintf('D-%03d', $newSeq);
        
        // Simpan data
        Pegawai::create([
            'PegawaiID' => $pegawaiId,
            'Nama' => $request->Nama,
            'Jabatan' => $request->Jabatan,
            'TanggalMasuk' => $request->TanggalMasuk,
            'NoTelp' => $request->NoTelp,
            'email' => $request->email,
        ]);
        
        return redirect()->route('pegawai.index')
            ->with('success', 'Dokter berhasil ditambahkan.');
    }
    
    // Menampilkan detail pegawai
    public function show($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('pegawai.show', compact('pegawai'));
    }
    
    // Menampilkan form edit pegawai
    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('pegawai.edit', compact('pegawai'));
    }
    
    // Mengupdate data pegawai
    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama' => 'required',
            'Jabatan' => 'required',
            'TanggalMasuk' => 'required|date',
            'NoTelp' => 'required',
            'email' => 'required|email|unique:Pegawai,email,' . $id . ',PegawaiID',
        ]);
        
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->update($request->all());
        
        return redirect()->route('pegawai.index')
            ->with('success', 'Dokter berhasil diperbarui.');
    }
    
    // Menghapus pegawai
    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();
        
        return redirect()->route('pegawai.index')
            ->with('success', 'Dokter berhasil dihapus.');
    }
}