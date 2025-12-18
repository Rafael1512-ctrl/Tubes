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
        Schema::create('pasiens', function (Blueprint $table) {
            $table->string('PasienID', 12)->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('Nama', 100);
            $table->date('TanggalLahir')->nullable();
            $table->string('Alamat', 100)->nullable();
            $table->string('NoTelp', 20)->nullable();
            $table->char('JenisKelamin', 1)->nullable(); // L or P
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
};
