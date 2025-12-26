<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasien';          // nama tabel di database
    protected $primaryKey = 'PasienID';   // primary key tabel
    public $timestamps = false;           // tabel pasien tidak punya created_at/updated_at

    protected $fillable = [
        'PasienID',
        'Nama',
        'TanggalLahir',
        'Alamat',
        'NoTelp',
        'JenisKelamin',
        'user_id', // kalau kamu hubungkan ke tabel users
    ];

    // Relasi ke tabel users (opsional, kalau pasien bisa login)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke rekam medis (kalau ada tabel rekammedis)
    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'PasienID');
    }

    // Relasi ke booking/jadwal (kalau ada tabel booking)
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'PasienID');
    }
}