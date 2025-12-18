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
        Schema::create('bookings', function (Blueprint $table) {
            $table->string('IdBooking', 11)->primary();
            $table->string('IdJadwal', 11);
            $table->foreign('IdJadwal')->references('IdJadwal')->on('jadwals')->onDelete('cascade');
            $table->string('PasienID', 12);
            $table->foreign('PasienID')->references('PasienID')->on('pasiens')->onDelete('cascade');
            $table->dateTime('TanggalBooking')->useCurrent();
            $table->string('Status', 20)->default('PRESENT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
