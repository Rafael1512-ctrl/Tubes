<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';
    protected $primaryKey = 'IdObat';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['IdObat', 'IdJenisObat', 'NamaObat', 'Satuan', 'Harga', 'Stok'];

    protected $casts = [
        'Harga' => 'decimal:2',
        'Stok' => 'integer'
    ];

    public function jenisObat()
    {
        return $this->belongsTo(JenisObat::class, 'IdJenisObat', 'JenisObatID');
    }
}
