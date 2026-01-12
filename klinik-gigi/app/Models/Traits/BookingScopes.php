<?php

namespace App\Models\Traits;

trait BookingScopes
{
    public function scopeByStatus($query, $status)
    {
        return $query->where('Status', $status);
    }

    public function scopeByPasien($query, $pasienId)
    {
        return $query->where('PasienID', $pasienId);
    }

    public function scopeByJadwal($query, $jadwalId)
    {
        return $query->where('IdJadwal', $jadwalId);
    }

    public function scopeActive($query)
    {
        return $query->where('Status', '!=', 'CANCELLED');
    }
}
