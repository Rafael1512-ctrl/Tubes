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
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        // Cari user berdasarkan email
        $user = User::where('email', $credentials['email'])->first();
        
        // Jika user ditemukan dan password cocok
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Login user
            Auth::login($user, $request->remember ? true : false);
            
            // Redirect berdasarkan role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('dashboard');
                case 'dokter':
                    return redirect()->route('dashboard');
                case 'pasien':
                    return redirect()->route('dashboard');
                default:
                    return redirect()->route('dashboard');
            }
        }
        
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }
    
    // Tampilkan halaman registrasi
    public function showRegister()
    {
        return view('auth.register');
    }
    
    // Proses registrasi
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:Users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:pasien,dokter,staff',
        ]);
        
        // Buat user baru
        $user = User::create([
            'username' => $request->email, // gunakan email sebagai username
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        
        // Jika role pasien, buat data pasien juga
        if ($request->role == 'pasien') {
            // Anda perlu membuat PasienController untuk ini
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