<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewObat extends Model
{
    protected $table = 'v_obat_lengkap';
    protected $primaryKey = 'IdObat';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function save(array $options = []) { return false; }
    public function update(array $attributes = [], array $options = []) { return false; }
}
