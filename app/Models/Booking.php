<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'IdBooking';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'IdBooking',
        'PasienID',
        'IdJadwal',
        'TanggalBooking',
        'Status',
    ];

    // Relasi ke Jadwal
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'IdJadwal');
    }

    // Relasi ke Pasien
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'PasienID');
    }
}