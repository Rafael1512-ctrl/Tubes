<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewBooking extends Model
{
    use Traits\BookingScopes;
    protected $table = 'v_booking_lengkap';
    protected $primaryKey = 'IdBooking';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $casts = [
        'TanggalBooking' => 'datetime',
        'tanggal_jadwal' => 'date',
        'JamMulai' => 'datetime',
        'JamAkhir' => 'datetime',
        'CancelledAt' => 'datetime'
    ];

    /**
     * Replicate accessors from Jadwal for convenience
     */
    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal_jadwal ? $this->tanggal_jadwal->format('d M Y') : '-';
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

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'PasienID', 'PasienID');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'IdJadwal', 'IdJadwal');
    }

    // View models are usually read-only
    public function save(array $options = []) { return false; }
    public function update(array $attributes = [], array $options = []) { return false; }
}
