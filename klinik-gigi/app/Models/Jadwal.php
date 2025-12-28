<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';
    protected $primaryKey = 'IdJadwal';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'IdJadwal',
        'IdDokter',
        'Tanggal',
        'JamMulai',
        'JamAkhir',
        'Status',
        'Kapasitas'
    ];

    protected $casts = [
        'Tanggal' => 'date',
        'JamMulai' => 'datetime:H:i',
        'JamAkhir' => 'datetime:H:i',
        'Kapasitas' => 'integer'
    ];

    /**
     * Relationship: Jadwal belongs to Pegawai (Dokter)
     */
    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'IdDokter', 'PegawaiID');
    }

    /**
     * Relationship: Jadwal has many Booking
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'IdJadwal', 'IdJadwal');
    }

    /**
     * Accessor: Get formatted tanggal
     */
    public function getFormattedTanggalAttribute()
    {
        return $this->Tanggal ? $this->Tanggal->format('d M Y') : '-';
    }

    /**
     * Accessor: Get formatted jam
     */
    public function getFormattedJamAttribute()
    {
        return $this->JamMulai->format('H:i') . ' - ' . $this->JamAkhir->format('H:i');
    }

    /**
     * Accessor: Get sesi (Pagi/Sore)
     */
    public function getSesiAttribute()
    {
        $jam = $this->JamMulai->format('H');
        return $jam < 12 ? 'Pagi' : 'Sore';
    }

    /**
     * Accessor: Get jumlah booking aktif
     */
    public function getJumlahBookingAktifAttribute()
    {
        return $this->bookings()->where('Status', '!=', 'CANCELLED')->count();
    }

    /**
     * Accessor: Get sisa kapasitas
     */
    public function getSisaKapasitasAttribute()
    {
        return $this->Kapasitas - $this->jumlah_booking_aktif;
    }

    /**
     * Accessor: Check if jadwal is full
     */
    public function getIsFullAttribute()
    {
        return $this->sisa_kapasitas <= 0;
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('Status', $status);
    }

    /**
     * Scope: Filter by tanggal
     */
    public function scopeByTanggal($query, $tanggal)
    {
        return $query->whereDate('Tanggal', $tanggal);
    }

    /**
     * Scope: Filter by dokter
     */
    public function scopeByDokter($query, $dokterId)
    {
        return $query->where('IdDokter', $dokterId);
    }

    /**
     * Scope: Filter by bulan
     */
    public function scopeByBulan($query, $year, $month)
    {
        return $query->whereYear('Tanggal', $year)
                     ->whereMonth('Tanggal', $month);
    }

    /**
     * Scope: Only available jadwal (future or ongoing today)
     */
    public function scopeAvailable($query)
    {
        return $query->where('Status', 'Available')
                     ->where(function($q) {
                         $q->where('Tanggal', '>', now()->toDateString())
                           ->orWhere(function($sq) {
                               $sq->where('Tanggal', now()->toDateString())
                                  ->whereRaw('TIMESTAMPDIFF(MINUTE, NOW(), CONCAT(Tanggal, " ", JamAkhir)) >= 60');
                           });
                     });
    }

    /**
     * Static Method: Auto Update Status for Expired Schedules
     * Marks 'Available' schedules as 'Not Available' if less than 1 hour remains before JamAkhir
     */
    public static function autoUpdateStatus()
    {
        return self::where('Status', 'Available')
            ->where(function($q) {
                // Tanggal sudah lewat
                $q->where('Tanggal', '<', now()->toDateString())
                  // Atau hari ini dan sisa waktu kurang dari 60 menit dari JamAkhir
                  ->orWhere(function($sq) {
                      $sq->where('Tanggal', now()->toDateString())
                         ->whereRaw('TIMESTAMPDIFF(MINUTE, NOW(), CONCAT(Tanggal, " ", JamAkhir)) < 60');
                  });
            })
            ->update(['Status' => 'Not Available']);
    }
}
