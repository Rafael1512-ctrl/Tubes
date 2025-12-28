<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisObat extends Model
{
    protected $table = 'jenisobat';
    protected $primaryKey = 'JenisObatID';
    public $timestamps = false;

    protected $fillable = ['NamaJenis'];

    public function obats()
    {
        return $this->hasMany(Obat::class, 'IdJenisObat', 'JenisObatID');
    }
}
