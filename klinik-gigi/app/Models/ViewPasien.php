<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewPasien extends Model
{
    protected $table = 'v_pasien_user';
    protected $primaryKey = 'PasienID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'PasienID', 'PasienID');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'PasienID', 'PasienID');
    }

    public function save(array $options = []) { return false; }
    public function update(array $attributes = [], array $options = []) { return false; }
}
