<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    protected $table = 'rekam_medis';
    protected $primaryKey = 'IdRekamMedis';
    public $timestamps = true;
    public $incrementing = false;
    
    protected $fillable = [
        'IdRekamMedis', 'IdBooking', 'PasienID', 'DokterID', 
        'Tanggal', 'Diagnosa', 'Catatan', 'Anamnesa', 'ResepDokter'
    ];
    
    // Relasi ke Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'IdBooking', 'IdBooking');
    }
    
    // Relasi ke Pasien
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'PasienID', 'PasienID');
    }
    
    // Relasi ke Dokter (Pegawai)
    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'DokterID', 'PegawaiID');
    }
    
    // Relasi ke Obat
    public function obat()
    {
        return $this->belongsToMany(Obat::class, 'RekamMedis_Obat', 'IdRekamMedis', 'IdObat')
            ->withPivot('Dosis', 'Frekuensi', 'LamaHari', 'Jumlah', 'HargaSatuan');
    }
    
    // Relasi ke Tindakan
    public function tindakan()
    {
        return $this->belongsToMany(Tindakan::class, 'RekamMedis_Tindakan', 'IdRekamMedis', 'IdTindakan')
            ->withPivot('Jumlah', 'Harga');
    }
}