<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Obat extends Model
{
    protected $table = 'obats';
    protected $primaryKey = 'IdObat';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'IdObat',
        'IdJenisObat',
        'NamaObat',
        'Satuan',
        'Harga',
        'Stok',
        'exp_date',
    ];

    protected $dates = [
        'exp_date',
    ];

    // Relasi ke JenisObat
    public function jenisObat()
    {
        return $this->belongsTo(JenisObat::class, 'IdJenisObat', 'JenisObatID');
    }

    // Check if obat is expired
    public function isExpired()
    {
        if (!$this->exp_date) {
            return false;
        }
        return Carbon::parse($this->exp_date)->isPast();
    }

    // Check if obat is expiring soon (dalam 30 hari)
    public function isExpiringSoon($days = 30)
    {
        if (!$this->exp_date) {
            return false;
        }
        $expiryDate = Carbon::parse($this->exp_date);
        $checkDate = Carbon::now()->addDays($days);
        
        return $expiryDate->isFuture() && $expiryDate->lte($checkDate);
    }

    // Scope untuk filter obat yang belum expired
    public function scopeUnexpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('exp_date')
              ->orWhere('exp_date', '>=', Carbon::now()->toDateString());
        });
    }

    // Scope untuk filter obat yang akan expired
    public function scopeExpiringSoon($query, $days = 30)
    {
        $futureDate = Carbon::now()->addDays($days)->toDateString();
        return $query->whereNotNull('exp_date')
                     ->where('exp_date', '>=', Carbon::now()->toDateString())
                     ->where('exp_date', '<=', $futureDate);
    }

    // Scope untuk filter obat yang sudah expired
    public function scopeExpired($query)
    {
        return $query->whereNotNull('exp_date')
                     ->where('exp_date', '<', Carbon::now()->toDateString());
    }

    // Get status badge untuk UI
    public function getExpiryStatus()
    {
        if (!$this->exp_date) {
            return 'no-expiry';
        }
        
        if ($this->isExpired()) {
            return 'expired';
        }
        
        if ($this->isExpiringSoon(30)) {
            return 'expiring-soon';
        }
        
        return 'valid';
    }
}
