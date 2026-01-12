<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewPegawai extends Model
{
    protected $table = 'v_pegawai_user';
    protected $primaryKey = 'PegawaiID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function save(array $options = []) { return false; }
    public function update(array $attributes = [], array $options = []) { return false; }
}
