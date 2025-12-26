<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create() {
        return view('admin.users.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $user->update($request->only('name','email','role'));
        return redirect()->route('admin.users')->with('success', 'User berhasil diupdate');
    }

    public function destroy($id) {
        User::destroy($id);
        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus');
    }

    public function storePegawai(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'tanggal_masuk' => 'required|date',
            'no_telp' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // 1. Buat akun login di tabel users
        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->jabatan, // misalnya role = jabatan
        ]);

        // 2. Buat data pegawai dan hubungkan ke user_id
        Pegawai::create([
            'Nama' => $request->nama,
            'Jabatan' => $request->jabatan,
            'TanggalMasuk' => $request->tanggal_masuk,
            'NoTelp' => $request->no_telp,
            'user_id' => $user->id,
        ]);

        return redirect()->route('admin.pegawai')->with('success', 'Pegawai baru berhasil ditambahkan');
    }

}