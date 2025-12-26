<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // misalnya: admin, dokter, resepsionis, pasien
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
}