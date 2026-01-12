<?php

namespace App\Models\Traits;

trait JadwalScopes
{
    public function scopeByStatus($query, $status)
    {
        return $query->where('Status', $status);
    }

    public function scopeByTanggal($query, $tanggal)
    {
        return $query->whereDate('Tanggal', $tanggal);
    }

    public function scopeByDokter($query, $dokterId)
    {
        return $query->where('IdDokter', $dokterId);
    }

    public function scopeByBulan($query, $year, $month)
    {
        return $query->whereYear('Tanggal', $year)
                     ->whereMonth('Tanggal', $month);
    }

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
}
