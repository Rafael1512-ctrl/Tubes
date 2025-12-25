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
        Schema::create('tindakan_spesialis', function (Blueprint $table) {
            $table->string('IdTindakanSpesialis', 15)->primary();
            $table->string('PasienID', 12);
            $table->foreign('PasienID')->references('PasienID')->on('pasiens')->onDelete('cascade');
            $table->string('DokterID', 5);
            $table->foreign('DokterID')->references('PegawaiID')->on('pegawais')->onDelete('cascade');
            $table->string('IdRekamMedis', 15)->nullable();
            $table->foreign('IdRekamMedis')->references('IdRekamMedis')->on('rekam_medis')->onDelete('set null');
            $table->string('NamaTindakan', 200);
            $table->boolean('is_periodic')->default(true);
            $table->enum('frequency', ['weekly', 'monthly', 'custom'])->default('weekly');
            $table->integer('custom_days')->nullable(); // untuk frequency custom
            $table->integer('total_sessions');
            $table->integer('completed_sessions')->default(0);
            $table->text('plan_goal')->nullable();
            $table->date('start_date');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindakan_spesialis');
    }
};
