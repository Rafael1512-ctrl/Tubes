<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        $this->authorizeRole('admin');
        return view('dashboards.admin');
    }

    public function dokter()
    {
        $this->authorizeRole('dokter');
        return view('dashboards.dokter');
    }

    public function pasien()
    {
        $this->authorizeRole('pasien');
        return view('dashboards.pasien');
    }

    private function authorizeRole(string $role)
    {
        $user = Auth::user();
        abort_unless($user && $user->role === $role, 403, 'Unauthorized');
    }
}
