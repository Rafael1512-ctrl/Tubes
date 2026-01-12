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

    protected $fillable = ['IdObat', 'IdJenisObat', 'NamaObat', 'Satuan', 'HargaBeli', 'HargaJual', 'Harga', 'Stok'];

    protected $casts = [
        'HargaBeli' => 'decimal:2',
        'HargaJual' => 'decimal:2',
        'Harga' => 'decimal:2',
        'Stok' => 'integer'
    ];

    public function jenisObat()
    {
        return $this->belongsTo(JenisObat::class, 'IdJenisObat', 'JenisObatID');
    }
}
