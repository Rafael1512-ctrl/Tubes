<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];
}
