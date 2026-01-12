<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasien';
    protected $primaryKey = 'PasienID'; // HARUS 'PasienID' bukan 'pasienid'
    public $incrementing = false;
    public $timestamps = false;

    // SESUAIKAN DENGAN DATABASE (huruf kapital)
    protected $fillable = [
        'PasienID',
        'user_id',
        'Nama',           // Database: Nama (huruf kapital)
        'TanggalLahir',   // Database: TanggalLahir (camelCase)
        'Alamat',         // Database: Alamat
        'NoTelp',         // Database: NoTelp (bukan notelp)
        'JenisKelamin',   // Database: JenisKelamin
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke rekam medis
    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'PasienID', 'PasienID');
    }

    // Relasi ke booking/jadwal
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'PasienID', 'PasienID');
    }
}