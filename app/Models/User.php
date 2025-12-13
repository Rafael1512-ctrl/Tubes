<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'Users';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'pegawai_id',
        'pasien_id',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke Pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'PegawaiID');
    }

    // Relasi ke Pasien
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id', 'PasienID');
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