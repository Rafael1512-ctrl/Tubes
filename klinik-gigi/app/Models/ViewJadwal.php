<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewJadwal extends Model
{
    use Traits\JadwalScopes;
    protected $table = 'v_jadwal_lengkap';
    protected $primaryKey = 'IdJadwal';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $casts = [
        'Tanggal' => 'date',
        'JamMulai' => 'datetime',
        'JamAkhir' => 'datetime'
    ];

    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'IdDokter', 'PegawaiID');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'IdJadwal', 'IdJadwal');
    }

    public function getFormattedTanggalAttribute()
    {
        return $this->Tanggal ? $this->Tanggal->format('d M Y') : '-';
    }

    public function getFormattedJamAttribute()
    {
        if (!$this->JamMulai || !$this->JamAkhir) return '-';
        return $this->JamMulai->format('H:i') . ' - ' . $this->JamAkhir->format('H:i');
    }

    public function getSesiAttribute()
    {
        if (!$this->JamMulai) return '-';
        return $this->JamMulai->format('H') < 12 ? 'Pagi' : 'Sore';
    }

    public function save(array $options = []) { return false; }
    public function update(array $attributes = [], array $options = []) { return false; }
}
