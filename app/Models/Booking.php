<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'Booking';
    protected $primaryKey = 'IdBooking';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'IdBooking',
        'IdJadwal',
        'PasienID',
        'Keluhan',
        'TanggalBooking',
        'Status',
        'created_at',
    ];

    // Relasi ke Jadwal
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'IdJadwal', 'IdJadwal');
    }

    // Relasi ke Pasien
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'PasienID', 'PasienID');
    }
}