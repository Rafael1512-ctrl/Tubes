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
        Schema::create('obats', function (Blueprint $table) {
            $table->string('IdObat', 7)->primary();
            $table->foreignId('IdJenisObat')->constrained('jenis_obats', 'JenisObatID');
            $table->string('NamaObat', 100);
            $table->string('Satuan', 20)->nullable();
            $table->decimal('Harga', 12, 2);
            $table->integer('Stok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obats');
    }
};
