<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawais';
    protected $primaryKey = 'PegawaiID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'PegawaiID',
        'user_id',
        'Nama',
        'Jabatan',
        'TanggalMasuk',
        'NoTelp',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Jadwal
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'IdDokter');
    }
}