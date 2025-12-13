<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'Pasien';
    protected $primaryKey = 'PasienID';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'PasienID',
        'Nama',
        'TanggalLahir',
        'Alamat',
        'NoTelp',
        'email',
        'JenisKelamin',
        'created_at',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->hasOne(User::class, 'pasien_id', 'PasienID');
    }

    // Relasi ke Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'PasienID', 'PasienID');
    }
}