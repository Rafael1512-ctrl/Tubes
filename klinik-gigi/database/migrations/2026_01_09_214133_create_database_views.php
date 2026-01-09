<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. View Jadwal Lengkap (Jadwal + Info Dokter)
        DB::statement("
            CREATE OR REPLACE VIEW v_jadwal_lengkap AS
            SELECT 
                j.*, 
                p.Nama as nama_dokter, 
                p.Jabatan as jabatan_dokter
            FROM jadwal j
            LEFT JOIN pegawai p ON j.IdDokter = p.PegawaiID
        ");

        // 2. View Booking Lengkap (Booking + Jadwal + Pasien + Dokter)
        DB::statement("
            CREATE OR REPLACE VIEW v_booking_lengkap AS
            SELECT 
                b.*,
                p.Nama as nama_pasien,
                j.Tanggal as tanggal_jadwal,
                j.JamMulai,
                j.JamAkhir,
                d.Nama as nama_dokter
            FROM booking b
            LEFT JOIN pasien p ON b.PasienID = p.PasienID
            LEFT JOIN jadwal j ON b.IdJadwal = j.IdJadwal
            LEFT JOIN pegawai d ON j.IdDokter = d.PegawaiID
        ");

        // 3. View Rekam Medis Lengkap
        DB::statement("
            CREATE OR REPLACE VIEW v_rekam_medis_lengkap AS
            SELECT 
                rm.*,
                p.Nama as nama_pasien,
                d.Nama as nama_dokter
            FROM rekammedis rm
            LEFT JOIN pasien p ON rm.PasienID = p.PasienID
            LEFT JOIN pegawai d ON rm.DokterID = d.PegawaiID
        ");

        // 4. View Obat Lengkap
        DB::statement("
            CREATE OR REPLACE VIEW v_obat_lengkap AS
            SELECT 
                o.*,
                jo.NamaJenis as nama_jenis
            FROM obat o
            LEFT JOIN jenisobat jo ON o.IdJenisObat = jo.JenisObatID
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS v_jadwal_lengkap");
        DB::statement("DROP VIEW IF EXISTS v_booking_lengkap");
        DB::statement("DROP VIEW IF EXISTS v_rekam_medis_lengkap");
        DB::statement("DROP VIEW IF EXISTS v_obat_lengkap");
    }
};
