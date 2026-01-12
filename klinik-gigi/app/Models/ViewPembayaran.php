<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewPembayaran extends Model
{
    protected $table = 'v_pembayaran_lengkap';
    protected $primaryKey = 'IdPembayaran';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $casts = [
        'TanggalPembayaran' => 'datetime',
        'TotalBayar' => 'decimal:2',
        'tanggal_periksa' => 'date'
    ];

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'IdRekamMedis');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'PasienID');
    }

    public function save(array $options = []) { return false; }
    public function update(array $attributes = [], array $options = []) { return false; }
}
