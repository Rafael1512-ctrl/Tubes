<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $logs = DB::table('obat_log')
            ->where('Aksi', 'KELUAR')
            ->whereNull('IdRekamMedis')
            ->get();

        foreach ($logs as $log) {
            $logDate = date('Y-m-d', strtotime($log->Tanggal));
            
            $match = DB::table('rekammedis_obat')
                ->join('rekammedis', 'rekammedis_obat.IdRekamMedis', '=', 'rekammedis.IdRekamMedis')
                ->where('rekammedis_obat.IdObat', $log->IdObat)
                ->where('rekammedis_obat.Jumlah', $log->Jumlah)
                ->where('rekammedis.Tanggal', $logDate)
                ->select('rekammedis_obat.IdRekamMedis')
                ->first();
                
            if ($match) {
                DB::table('obat_log')
                    ->where('LogID', $log->LogID)
                    ->update(['IdRekamMedis' => $match->IdRekamMedis]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse this without losing original NULLs, 
        // but it's a data fix so it's fine.
    }
};
