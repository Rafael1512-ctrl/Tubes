<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tindakan extends Model
{
    protected $table = 'tindakans';
    protected $primaryKey = 'IdTindakan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'IdTindakan',
        'NamaTindakan',
        'Harga',
        'Durasi',
    ];

    public function rekamMedis()
    {
        return $this->belongsToMany(RekamMedis::class, 'rekam_medis_tindakans', 'IdTindakan', 'IdRekamMedis');
    }
}
