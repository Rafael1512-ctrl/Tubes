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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->string('IdJadwal', 11)->primary();
            $table->string('IdDokter', 5);
            $table->foreign('IdDokter')->references('PegawaiID')->on('pegawais')->onDelete('cascade');
            $table->date('Tanggal');
            $table->time('JamMulai');
            $table->time('JamAkhir');
            $table->string('Status', 20)->default('Available');
            $table->integer('Kapasitas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
