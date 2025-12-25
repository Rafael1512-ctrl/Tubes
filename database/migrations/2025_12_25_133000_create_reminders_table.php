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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('IdSesiTindakan');
            $table->foreign('IdSesiTindakan')->references('id')->on('sesi_tindakan')->onDelete('cascade');
            $table->enum('reminder_type', ['H-1', 'H-3']); // H-1 = 1 hari sebelum, H-3 = 3 hari sebelum
            $table->date('reminder_date');
            $table->boolean('is_sent')->default(false);
            $table->dateTime('sent_at')->nullable();
            $table->enum('recipient_type', ['staff', 'patient'])->default('staff');
            $table->string('recipient_id', 50)->nullable(); // user_id atau email/phone
            $table->timestamps();

            // Index untuk performance
            $table->index(['reminder_date', 'is_sent']);
            $table->index('IdSesiTindakan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
