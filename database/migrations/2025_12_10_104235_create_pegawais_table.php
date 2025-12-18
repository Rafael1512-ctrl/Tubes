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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->string('PegawaiID', 5)->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Nullable for non-login employees
            $table->string('Nama', 100);
            $table->string('Jabatan', 50)->nullable();
            $table->date('TanggalMasuk')->nullable();
            $table->string('NoTelp', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
