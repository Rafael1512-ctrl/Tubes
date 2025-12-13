<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'Jadwal';
    protected $primaryKey = 'IdJadwal';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'IdJadwal',
        'IdDokter',
        'Tanggal',
        'JamMulai',
        'JamAkhir',
        'Status',
        'Kapasitas',
        'created_at',
    ];

    // Relasi ke Dokter
    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'IdDokter', 'PegawaiID');
    }

    // Relasi ke Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'IdJadwal', 'IdJadwal');
    }
}