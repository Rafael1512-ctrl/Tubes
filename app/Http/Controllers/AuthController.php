<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Pasien;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return redirect('/'); // Redirect ke home karena login sudah pakai modal
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'no_telp' => 'required|string|max:15',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => 'pasien',
            ]);

            // Generate PasienID (Format: P-YYYY-xxxxx)
            $year = date('Y');
            $prefix = "P-$year-";
            $lastPasien = Pasien::where('PasienID', 'like', $prefix . '%')
                ->orderBy('PasienID', 'desc')
                ->first();
            
            if ($lastPasien) {
                $lastNum = (int) substr($lastPasien->PasienID, 7);
                $newNum = $lastNum + 1;
            } else {
                $newNum = 1;
            }
            $pasienId = $prefix . str_pad($newNum, 5, '0', STR_PAD_LEFT);

            Pasien::create([
                'PasienID' => $pasienId,
                'user_id' => $user->id,
                'Nama' => $request->name,
                'NoTelp' => $request->no_telp,
                'TanggalLahir' => $request->tanggal_lahir,
                'Alamat' => $request->alamat,
                'JenisKelamin' => $request->jenis_kelamin,
            ]);

            event(new Registered($user));

            Auth::login($user);

            DB::commit();

            return redirect()->route('verification.notice')->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftar: ' . $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // redirect sesuai role
            return match ($user->role) {
                'admin'  => redirect('/admin/dashboard'),
                'dokter' => redirect('/dokter/dashboard'),
                default  => redirect('/pasien/dashboard'),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}