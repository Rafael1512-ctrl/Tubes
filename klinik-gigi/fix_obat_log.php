<?php

use Illuminate\Support\Facades\DB;
use App\Models\ObatLog;

// Fix existing NULL IdRekamMedis in obat_log for 'KELUAR' aksi
// We match based on IdObat, Jumlah (scaled to decimal), and proximity of date if possible, 
// but since this is mock data, we can match exactly with rekammedis_obat.

$logs = DB::table('obat_log')
    ->where('Aksi', 'KELUAR')
    ->whereNull('IdRekamMedis')
    ->get();

foreach ($logs as $log) {
    // Find matching rekammedis_obat
    // There might be multiple, so we pick the one where the RekamMedis date matches the Log date (Y-m-d)
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
        echo "Updated LogID {$log->LogID} with IdRekamMedis {$match->IdRekamMedis}\n";
    } else {
        echo "No match found for LogID {$log->LogID} (Obat: {$log->IdObat}, Qty: {$log->Jumlah}, Date: {$logDate})\n";
    }
}
