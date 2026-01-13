<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('obat_log')) {
            Schema::create('obat_log', function (Blueprint $table) {
                $table->integer('LogID')->autoIncrement();
                $table->string('IdObat', 7);
                $table->dateTime('Tanggal')->useCurrent();
                $table->string('Aksi', 50);
                $table->decimal('Jumlah', 12, 2);
                $table->decimal('StokSebelum', 12, 2);
                $table->decimal('StokSesudah', 12, 2);
                $table->string('IdRekamMedis', 15)->nullable();
                $table->string('CreatedBy', 50)->nullable();
                
                $table->primary('LogID');
                // Indexes or FKs if needed
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat_log');
    }
};
