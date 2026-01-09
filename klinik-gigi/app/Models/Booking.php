<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'v_booking_lengkap';
    protected $primaryKey = 'IdBooking';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'IdBooking',
        'IdJadwal',
        'PasienID',
        'TanggalBooking',
        'Status',
        'CancelledAt'
    ];

    protected $casts = [
        'TanggalBooking' => 'datetime',
        'CancelledAt' => 'datetime'
    ];

    /**
     * Relationship: Booking belongs to Jadwal
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'IdJadwal', 'IdJadwal');
    }

    /**
     * Relationship: Booking belongs to Pasien
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'PasienID', 'PasienID');
    }

    /**
     * Accessor: Get formatted tanggal booking
     */
    public function getFormattedTanggalBookingAttribute()
    {
        return $this->TanggalBooking ? $this->TanggalBooking->format('d M Y, H:i') : '-';
    }

    /**
     * Accessor: Check if booking is active
     */
    public function getIsActiveAttribute()
    {
        return $this->Status !== 'CANCELLED';
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('Status', $status);
    }

    /**
     * Scope: Filter by pasien
     */
    public function scopeByPasien($query, $pasienId)
    {
        return $query->where('PasienID', $pasienId);
    }

    /**
     * Scope: Filter by jadwal
     */
    public function scopeByJadwal($query, $jadwalId)
    {
        return $query->where('IdJadwal', $jadwalId);
    }

    /**
     * Scope: Only active bookings
     */
    public function scopeActive($query)
    {
        return $query->where('Status', '!=', 'CANCELLED');
    }

    /**
     * Scope: Filter by tanggal range
     */
    public function scopeByTanggalRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('TanggalBooking', [$startDate, $endDate]);
    }

    /**
     * Static Method: Auto Cancel Expired Bookings
     * Cancels bookings with status 'PRESENT' if the schedule time has passed.
     */
    public static function autoCancelExpired()
    {
        // Update bookings where status is PRESENT and schedule end time has passed
        return self::where('Status', 'PRESENT')
            ->whereHas('jadwal', function($q) {
                $q->where(function($sq) {
                    // Date has passed
                    $sq->where('Tanggal', '<', now()->toDateString())
                       // Or today, but time has passed
                       ->orWhere(function($ssq) {
                           $ssq->where('Tanggal', now()->toDateString())
                               ->where('JamAkhir', '<', now()->format('H:i:s'));
                       });
                });
            })
            ->update([
                'Status' => 'CANCELLED',
                'CancelledAt' => now()
            ]);
    }
}
