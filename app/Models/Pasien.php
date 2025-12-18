<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasiens';
    protected $primaryKey = 'PasienID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'PasienID',
        'user_id',
        'Nama',
        'Alamat',
        'NoTelp',
        'TanggalLahir',
        'JenisKelamin',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'PasienID');
    }
}