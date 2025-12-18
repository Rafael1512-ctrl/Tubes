<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayarans';
    protected $primaryKey = 'IdPembayaran';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'IdPembayaran',
        'IdRekamMedis',
        'PasienID',
        'TanggalPembayaran',
        'Metode',
        'TotalBayar',
        'Status',
    ];

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'IdRekamMedis', 'IdRekamMedis');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'PasienID', 'PasienID');
    }
}
