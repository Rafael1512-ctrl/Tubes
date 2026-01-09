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
        // 5. View Pembayaran Lengkap
        DB::statement("
            CREATE OR REPLACE VIEW v_pembayaran_lengkap AS
            SELECT 
                p.*,
                rm.Tanggal as tanggal_periksa,
                ps.Nama as nama_pasien
            FROM pembayaran p
            LEFT JOIN rekammedis rm ON p.IdRekamMedis = rm.IdRekamMedis
            LEFT JOIN pasien ps ON rm.PasienID = ps.PasienID
        ");

        // 6. View Pegawai & User
        DB::statement("
            CREATE OR REPLACE VIEW v_pegawai_user AS
            SELECT 
                p.*,
                u.email,
                u.name as display_name,
                u.role
            FROM pegawai p
            LEFT JOIN users u ON p.user_id = u.id
        ");

        // 7. View Pasien & User
        DB::statement("
            CREATE OR REPLACE VIEW v_pasien_user AS
            SELECT 
                p.*,
                u.email,
                u.name as display_name
            FROM pasien p
            LEFT JOIN users u ON p.user_id = u.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS v_pembayaran_lengkap");
        DB::statement("DROP VIEW IF EXISTS v_pegawai_user");
        DB::statement("DROP VIEW IF EXISTS v_pasien_user");
    }
};
