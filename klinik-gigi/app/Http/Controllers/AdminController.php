<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index() {
        $users = User::with(['pegawai', 'pasien'])->get();
        return view('admin.users.index', compact('users'));
    }

    public function create() {
        return view('admin.users.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,dokter,pegawai,pasien',
            'password' => 'required|min:6',
            // Validasi conditional untuk pegawai
            'jabatan' => 'required_if:role,admin,dokter,pegawai',
            'tanggal_masuk' => 'nullable|date',
            'no_telp' => 'nullable|string',
            // Validasi conditional untuk pasien
            'alamat' => 'required_if:role,pasien|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
        ]);

        DB::beginTransaction();

        try {
            \Log::info('Mulai proses tambah user', ['email' => $request->email, 'role' => $request->role]);
            
            // 1. Buat user di tabel users
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]);
            
            \Log::info('User berhasil dibuat', ['user_id' => $user->id]);

            // 2. Jika role adalah admin/dokter/pegawai, buat data pegawai menggunakan store procedure
            if (in_array($request->role, ['admin', 'dokter', 'pegawai'])) {
                \Log::info('Memanggil Sp_InsertPegawai', ['user_id' => $user->id]);
                
                // Panggil store procedure Sp_InsertPegawai
                // Note: Stored procedure ini melakukan COMMIT sendiri
                DB::statement('CALL Sp_InsertPegawai(?, ?, ?, ?, ?, @new_id)', [
                    $request->name,          // p_Nama
                    $request->jabatan,       // p_Jabatan
                    $request->tanggal_masuk, // p_TanggalMasuk
                    $request->no_telp,       // p_NoTelp
                    $user->id,               // p_userid (user_id)
                ]);
                
                // Ambil output parameter dari store procedure
                $result = DB::select('SELECT @new_id as new_pegawai_id');
                \Log::info('Pegawai berhasil dibuat', ['pegawai_id' => $result[0]->new_pegawai_id ?? 'unknown']);
            }

            // 3. Jika role adalah pasien, buat data pasien menggunakan store procedure
            if ($request->role === 'pasien') {
                \Log::info('Memanggil Sp_InsertPasien', ['user_id' => $user->id]);
                
                // Panggil store procedure Sp_InsertPasien
                // Note: Stored procedure ini melakukan COMMIT sendiri
                DB::statement('CALL Sp_InsertPasien(?, ?, ?, ?, ?, ?, @new_pasien_id)', [
                    $user->id,               // p_user_id
                    $request->name,          // p_Nama
                    $request->tanggal_lahir, // p_TanggalLahir
                    $request->alamat,        // p_Alamat
                    $request->no_telp,       // p_NoTelp
                    $request->jenis_kelamin, // p_JenisKelamin
                ]);
                
                // Ambil output parameter dari store procedure
                $result = DB::select('SELECT @new_pasien_id as new_pasien_id');
                \Log::info('Pasien berhasil dibuat', ['pasien_id' => $result[0]->new_pasien_id ?? 'unknown']);
            }

            // Coba commit, tapi jika stored procedure sudah commit, tangkap exception-nya
            try {
                DB::commit();
                \Log::info('Transaction committed successfully', ['user_id' => $user->id]);
            } catch (\PDOException $e) {
                // Jika error "There is no active transaction", abaikan karena SP sudah commit
                if (strpos($e->getMessage(), 'no active transaction') !== false) {
                    \Log::info('Stored procedure already committed transaction', ['user_id' => $user->id]);
                } else {
                    throw $e; // Re-throw jika error lain
                }
            }
            
            return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan');

        } catch (\Exception $e) {
            // Hanya rollback jika masih ada transaction aktif
            try {
                DB::rollback();
            } catch (\PDOException $rollbackException) {
                // Abaikan error rollback jika tidak ada transaction
                \Log::warning('Rollback failed (no active transaction)', ['original_error' => $e->getMessage()]);
            }
            
            \Log::error('Gagal menambahkan user', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    public function edit($id) {
        $user = User::with(['pegawai', 'pasien'])->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,dokter,pegawai,pasien',
            'password' => 'nullable|min:6',
        ]);

        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            
            // Update data user
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ]);

            // Jika password diisi, update password
            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            // Update data pegawai jika role admin/dokter/pegawai
            if (in_array($request->role, ['admin', 'dokter', 'pegawai'])) {
                if ($user->pegawai) {
                    // Update data pegawai langsung ke tabel
                    $user->pegawai->update([
                        'Nama' => $request->name,
                        'Jabatan' => $request->jabatan ?? $user->pegawai->Jabatan,
                        'TanggalMasuk' => $request->tanggal_masuk ?? $user->pegawai->TanggalMasuk,
                        'NoTelp' => $request->no_telp ?? $user->pegawai->NoTelp,
                    ]);
                } else {
                    // Buat data pegawai baru menggunakan store procedure
                    DB::statement('CALL Sp_InsertPegawai(?, ?, ?, ?, ?, @new_id)', [
                        $request->name,
                        $request->jabatan,
                        $request->tanggal_masuk,
                        $request->no_telp,
                        $user->id,
                    ]);
                }

                // Hapus data pasien jika ada (karena berubah dari pasien ke pegawai)
                if ($user->pasien) {
                    $user->pasien->delete();
                }
            }

            // Update data pasien jika role pasien
            if ($request->role === 'pasien') {
                if ($user->pasien) {
                    // Update data pasien langsung ke tabel
                    $user->pasien->update([
                        'Nama' => $request->name,
                        'TanggalLahir' => $request->tanggal_lahir ?? $user->pasien->TanggalLahir,
                        'Alamat' => $request->alamat ?? $user->pasien->Alamat,
                        'NoTelp' => $request->no_telp ?? $user->pasien->NoTelp,
                        'JenisKelamin' => $request->jenis_kelamin ?? $user->pasien->JenisKelamin,
                    ]);
                } else {
                    // Buat data pasien baru menggunakan store procedure
                    DB::statement('CALL Sp_InsertPasien(?, ?, ?, ?, ?, ?, @new_pasien_id)', [
                        $user->id,
                        $request->name,
                        $request->tanggal_lahir,
                        $request->alamat,
                        $request->no_telp,
                        $request->jenis_kelamin,
                    ]);
                }

                // Hapus data pegawai jika ada (karena berubah dari pegawai ke pasien)
                if ($user->pegawai) {
                    $user->pegawai->delete();
                }
            }

            DB::commit();
            return redirect()->route('admin.users')->with('success', 'User berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate user: ' . $e->getMessage());
        }
    }

    public function destroy($id) {
        DB::beginTransaction();
        
        try {
            $user = User::findOrFail($id);
            
            // Hapus data pegawai jika ada
            if ($user->pegawai) {
                $user->pegawai->delete();
            }
            
            // Hapus data pasien jika ada
            if ($user->pasien) {
                $user->pasien->delete();
            }
            
            // Hapus user
            $user->delete();
            
            DB::commit();
            return redirect()->route('admin.users')->with('success', 'User berhasil dihapus');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}