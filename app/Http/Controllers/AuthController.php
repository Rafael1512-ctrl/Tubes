<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }
    
    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required', // Bisa email atau username
            'password' => 'required',
        ]);
        
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Cari user berdasarkan email atau username
        $user = User::where($loginType, $request->login)->first();
        
        // Jika user ditemukan dan password cocok
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Login user
            Auth::login($user, $request->remember ? true : false);
            return redirect()->route('dashboard');
        }
        
        return back()->withErrors([
            'email' => 'Username/Email atau password salah.',
        ]);
    }
    
    // Tampilkan halaman registrasi
    public function showRegister()
    {
        return view('auth.register');
    }
    
    // Proses registrasi
    // Proses registrasi
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'username' => 'nullable|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:pasien,dokter,admin',
        ]);
        
        // Buat user baru
        $user = User::create([
            'name' => $request->nama,
            'username' => $request->username ?? explode('@', $request->email)[0],
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        
        // Buat data profil berdasarkan role
        if ($request->role == 'pasien') {
            // Generate PasienID: P-YYYY-XXXXX
            $year = date('Y');
            $prefix = 'P-' . $year . '-';
            
            // Find max ID with this prefix
            $lastPasien = \App\Models\Pasien::where('PasienID', 'like', $prefix . '%')
                            ->orderBy('PasienID', 'desc')
                            ->first();
            
            if ($lastPasien) {
                $lastSeq = (int) substr($lastPasien->PasienID, -5);
                $newSeq = $lastSeq + 1;
            } else {
                $newSeq = 1;
            }
            
            $pasienID = $prefix . str_pad($newSeq, 5, '0', STR_PAD_LEFT);

            \App\Models\Pasien::create([
                'PasienID' => $pasienID,
                'user_id' => $user->id,
                'Nama' => $user->name,
                'NoTelp' => '-',
                'Alamat' => '-',
            ]);
        } elseif ($request->role == 'dokter' || $request->role == 'admin') {
            // Generate PegawaiID: A-XXX or D-XXX
            $prefix = ($request->role == 'admin') ? 'A-' : 'D-';
            
            $lastPegawai = \App\Models\Pegawai::where('PegawaiID', 'like', $prefix . '%')
                            ->orderBy('PegawaiID', 'desc')
                            ->first();

            if ($lastPegawai) {
                // Assuming format A-001, D-001 (seq length 3)
                $lastSeq = (int) substr($lastPegawai->PegawaiID, 2);
                $newSeq = $lastSeq + 1;
            } else {
                $newSeq = 1;
            }
            
            $pegawaiID = $prefix . str_pad($newSeq, 3, '0', STR_PAD_LEFT);

            \App\Models\Pegawai::create([
                'PegawaiID' => $pegawaiID,
                'user_id' => $user->id,
                'Nama' => $user->name,
                'Jabatan' => ucfirst($request->role), // Admin or Dokter
                'TanggalMasuk' => now(),
            ]);
        }
        
        // Auto login setelah registrasi
        Auth::login($user);
        
        return redirect()->route('dashboard');
    }
    
    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}