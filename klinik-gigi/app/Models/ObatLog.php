<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObatLog extends Model
{
    protected $table = 'obat_log';
    protected $primaryKey = 'LogID';
    public $timestamps = false; // Based on SQL schema, it uses current_timestamp() but no updated_at

    protected $fillable = [
        'IdObat',
        'Tanggal',
        'Aksi',
        'Jumlah',
        'StokSebelum',
        'StokSesudah',
        'IdRekamMedis',
        'CreatedBy'
    ];

    protected $casts = [
        'Tanggal' => 'datetime',
        'Jumlah' => 'decimal:2',
        'StokSebelum' => 'decimal:2',
        'StokSesudah' => 'decimal:2',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'IdObat', 'IdObat');
    }

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'IdRekamMedis', 'IdRekamMedis');
    }
}
