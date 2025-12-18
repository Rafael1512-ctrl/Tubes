<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tindakan
        Schema::create('tindakans', function (Blueprint $table) {
            $table->string('IdTindakan', 10)->primary();
            $table->string('NamaTindakan', 100);
            $table->decimal('Harga', 12, 2);
            $table->time('Durasi')->nullable();
            $table->timestamps();
        });

        // 2. RekamMedis
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->string('IdRekamMedis', 15)->primary();
            $table->string('IdBooking', 11);
            $table->foreign('IdBooking')->references('IdBooking')->on('bookings')->onDelete('cascade');
            $table->string('PasienID', 12);
            $table->foreign('PasienID')->references('PasienID')->on('pasiens')->onDelete('cascade');
            $table->string('DokterID', 5);
            $table->foreign('DokterID')->references('PegawaiID')->on('pegawais')->onDelete('cascade');
            $table->date('Tanggal')->useCurrent();
            $table->string('Diagnosa', 200);
            $table->string('Catatan', 500)->nullable();
            $table->timestamps();
        });

        // 3. RekamMedis_Obat (Pivot)
        Schema::create('rekam_medis_obats', function (Blueprint $table) {
            $table->string('IdRekamMedis', 15);
            $table->foreign('IdRekamMedis')->references('IdRekamMedis')->on('rekam_medis')->onDelete('cascade');
            $table->string('IdObat', 7);
            $table->foreign('IdObat')->references('IdObat')->on('obats')->onDelete('cascade');
            $table->string('Dosis', 50)->nullable();
            $table->string('Frekuensi', 50)->nullable();
            $table->integer('LamaHari')->nullable();
            $table->decimal('Jumlah', 12, 2);
            $table->decimal('HargaSatuan', 12, 2);
            // SubTotal is calculated, not stored usually, or computed column. 
            // In Laravel simpler to just store or calculate on retrieval.
            // SQL had computed column. We'll skip computed column for now or add if specific DB supports it.
            $table->primary(['IdRekamMedis', 'IdObat']);
            $table->timestamps();
        });

        // 4. RekamMedis_Tindakan (Pivot)
        Schema::create('rekam_medis_tindakans', function (Blueprint $table) {
            $table->string('IdRekamMedis', 15);
            $table->foreign('IdRekamMedis')->references('IdRekamMedis')->on('rekam_medis')->onDelete('cascade');
            $table->string('IdTindakan', 10);
            $table->foreign('IdTindakan')->references('IdTindakan')->on('tindakans')->onDelete('cascade');
            $table->integer('Jumlah')->default(1);
            $table->decimal('Harga', 12, 2);
            $table->primary(['IdRekamMedis', 'IdTindakan']);
            $table->timestamps();
        });

        // 5. Pembayaran
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->string('IdPembayaran', 15)->primary();
            $table->string('IdRekamMedis', 15);
            $table->foreign('IdRekamMedis')->references('IdRekamMedis')->on('rekam_medis')->onDelete('cascade');
            $table->string('PasienID', 12);
            $table->foreign('PasienID')->references('PasienID')->on('pasiens')->onDelete('cascade');
            $table->dateTime('TanggalPembayaran')->useCurrent();
            $table->string('Metode', 20); // Transfer, Cash
            $table->decimal('TotalBayar', 12, 2);
            $table->string('Status', 20)->default('UNPAID');
            $table->timestamps();
        });

        // 6. Obat_Log
        Schema::create('obat_logs', function (Blueprint $table) {
            $table->id('LogID'); // Auto-inc
            $table->string('IdObat', 7);
            $table->foreign('IdObat')->references('IdObat')->on('obats')->onDelete('cascade');
            $table->dateTime('Tanggal')->useCurrent();
            $table->string('Aksi', 50);
            $table->decimal('Jumlah', 12, 2);
            $table->decimal('StokSebelum', 12, 2);
            $table->decimal('StokSesudah', 12, 2);
            $table->string('IdRekamMedis', 15)->nullable();
            $table->string('CreatedBy', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat_logs');
        Schema::dropIfExists('pembayarans');
        Schema::dropIfExists('rekam_medis_tindakans');
        Schema::dropIfExists('rekam_medis_obats');
        Schema::dropIfExists('rekam_medis');
        Schema::dropIfExists('tindakans');
    }
};
