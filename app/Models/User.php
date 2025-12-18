<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke Pegawai
    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'user_id');
    }

    // Relasi ke Pasien
    public function pasien()
    {
        return $this->hasOne(Pasien::class, 'user_id');
    }

    // Helper method untuk cek role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDokter()
    {
        return $this->role === 'dokter';
    }

    public function isPasien()
    {
        return $this->role === 'pasien';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }
}