<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        return $this->Tanggal ? Carbon::parse($this->Tanggal)->isoFormat('dddd, D MMMM YYYY') : '-';
    }

    public function getFormattedJamMulaiAttribute()
    {
        return $this->JamMulai ? Carbon::parse($this->JamMulai)->format('H:i') : '-';
    }

    public function getFormattedJamSelesaiAttribute()
    {
        // Assuming JamAkhir corresponds to JamSelesai
        return $this->JamAkhir ? Carbon::parse($this->JamAkhir)->format('H:i') : '-';
    }

    public function getFormattedJamAttribute()
    {
        if (!$this->JamMulai || !$this->JamAkhir) return '-';
        return $this->formatted_jam_mulai . ' - ' . $this->formatted_jam_selesai;
    }

    public function getSesiAttribute()
    {
        if (!$this->JamMulai) return '-';
        $path = $this->JamMulai instanceof Carbon ? $this->JamMulai : Carbon::parse($this->JamMulai);
        return $path->hour < 12 ? 'Pagi' : 'Sore';
    }

    public function getJumlahBookingAktifAttribute()
    {
        return \App\Models\Booking::where('IdJadwal', $this->IdJadwal)
            ->where('Status', '!=', 'CANCELLED')
            ->count();
    }

    public function getSisaKapasitasAttribute()
    {
        // Assuming 'Kapasitas' is a column in the v_jadwal_lengkap view
        return $this->Kapasitas - $this->jumlah_booking_aktif;
    }

    public function getIsFullAttribute()
    {
        return $this->sisa_kapasitas <= 0;
    }

    public function save(array $options = []) { return false; }
    public function update(array $attributes = [], array $options = []) { return false; }
}
