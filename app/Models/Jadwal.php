<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwals';
    protected $primaryKey = 'IdJadwal';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'IdJadwal',
        'IdDokter',
        'Tanggal',
        'JamMulai',
        'JamAkhir',
        'Status',
        'Kapasitas',
    ];

    // Relasi ke Dokter
    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'IdDokter');
    }

    // Relasi ke Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'IdJadwal');
    }
}