<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisObat extends Model
{
    protected $table = 'jenis_obats';
    protected $primaryKey = 'JenisObatID'; // Integer Auto Inc
    public $timestamps = true;

    protected $fillable = [
        'NamaJenis',
    ];

    public function obats()
    {
        return $this->hasMany(Obat::class, 'IdJenisObat', 'JenisObatID');
    }
}
