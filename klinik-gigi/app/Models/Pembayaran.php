<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'IdPembayaran';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'IdPembayaran', 'IdRekamMedis', 'PasienID', 'TanggalPembayaran', 'Metode', 'TotalBayar', 'Status'
    ];

    protected $casts = [
        'TanggalPembayaran' => 'datetime',
        'TotalBayar' => 'decimal:2'
    ];

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'IdRekamMedis');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'PasienID');
    }
}
