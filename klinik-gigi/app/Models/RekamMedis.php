<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    protected $table = 'rekammedis';
    protected $primaryKey = 'IdRekamMedis';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'IdRekamMedis', 'IdBooking', 'PasienID', 'DokterID', 'Tanggal', 'Diagnosa', 'Catatan'
    ];

    protected $casts = [
        'Tanggal' => 'date'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'IdBooking');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'PasienID');
    }

    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'DokterID');
    }

    public function tindakan()
    {
        return $this->belongsToMany(Tindakan::class, 'rekammedis_tindakan', 'IdRekamMedis', 'IdTindakan')
                    ->withPivot('Jumlah', 'Harga');
    }

    public function obat()
    {
        return $this->belongsToMany(Obat::class, 'rekammedis_obat', 'IdRekamMedis', 'IdObat')
                    ->withPivot('Dosis', 'Frekuensi', 'LamaHari', 'Jumlah', 'HargaSatuan');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'IdRekamMedis');
    }
}
