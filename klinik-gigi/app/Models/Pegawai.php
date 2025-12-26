<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai'; // pastikan sesuai nama tabel
    protected $fillable = [
        'nama',
        'email',
        'jabatan',   // misalnya: dokter, admin, resepsionis
        'password',
    ];
}