<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tindakan extends Model
{
    protected $table = 'tindakan';
    protected $primaryKey = 'IdTindakan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['IdTindakan', 'NamaTindakan', 'Kategori', 'Harga'];

    protected $casts = [
        'Harga' => 'decimal:2'
    ];
}
