<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'Pegawai';
    protected $primaryKey = 'PegawaiID';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'PegawaiID',
        'Nama',
        'Jabatan',
        'TanggalMasuk',
        'NoTelp',
        'email',
        'created_at',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->hasOne(User::class, 'pegawai_id', 'PegawaiID');
    }

    // Relasi ke Jadwal
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'IdDokter', 'PegawaiID');
    }
}