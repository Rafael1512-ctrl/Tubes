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
        Schema::create('sesi_tindakan', function (Blueprint $table) {
            $table->id();
            $table->string('IdTindakanSpesialis', 15);
            $table->foreign('IdTindakanSpesialis')->references('IdTindakanSpesialis')->on('tindakan_spesialis')->onDelete('cascade');
            $table->integer('session_number');
            $table->date('scheduled_date');
            $table->date('actual_date')->nullable();
            $table->enum('status', ['scheduled', 'attended', 'cancelled', 'rescheduled', 'completed'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->string('reschedule_reason', 200)->nullable();
            $table->timestamps();

            // Index untuk performance
            $table->index(['IdTindakanSpesialis', 'scheduled_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_tindakan');
    }
};
