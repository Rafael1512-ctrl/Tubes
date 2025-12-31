<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';
    protected $primaryKey = 'PegawaiID'; // HARUS 'PegawaiID' bukan 'pegawaiid'
    public $incrementing = false;
    public $timestamps = false;

    // SESUAIKAN DENGAN DATABASE (huruf kapital)
    protected $fillable = [
        'user_id',
        'Nama',           // Database: Nama (huruf kapital)
        'Jabatan',        // Database: Jabatan
        'TanggalMasuk',   // Database: TanggalMasuk (camelCase)
        'NoTelp',         // Database: NoTelp (bukan notelp)
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}