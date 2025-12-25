<?php

namespace App\Services;

use App\Models\Reminder;
use App\Models\SesiTindakan;
use Carbon\Carbon;

class ReminderService
{
    /**
     * Generate reminders untuk semua sesi yang akan datang
     */
    public function generateReminders()
    {
        $today = Carbon::now();
        $futureLimit = $today->copy()->addDays(7); // Generate untuk 7 hari ke depan
        
        // Ambil semua sesi scheduled yang belum punya reminder
        $sessions = SesiTindakan::where('status', 'scheduled')
                                ->whereBetween('scheduled_date', [$today, $futureLimit])
                                ->get();
        
        $remindersCreated = 0;
        
        foreach ($sessions as $session) {
            $session->generateReminders();
            $remindersCreated += $session->reminders()->count();
        }
        
        return $remindersCreated;
    }
    
    /**
     * Send semua pending reminders
     */
    public function sendPendingReminders()
    {
        $reminders = Reminder::pending()->with('sesiTindakan.tindakanSpesialis.pasien')->get();
        
        $sentCount = 0;
        
        foreach ($reminders as $reminder) {
            if ($reminder->recipient_type === 'staff') {
                $this->sendStaffReminder($reminder);
                $sentCount++;
            } elseif ($reminder->recipient_type === 'patient') {
                // Opsional - untuk pasien
                $this->sendPatientReminder($reminder);
                $sentCount++;
            }
        }
        
        return $sentCount;
    }
    
    /**
     * Send reminder ke staff
     */
    protected function sendStaffReminder($reminder)
    {
        $sesi = $reminder->sesiTindakan;
        $tindakan = $sesi->tindakanSpesialis;
        $pasien = $tindakan->pasien;
        
        // Format pesan
        $message = sprintf(
            "REMINDER %s:\nPasien: %s\nTindakan: %s\nSesi ke-%d\nTanggal: %s\n",
            $reminder->reminder_type,
            $pasien->Nama,
            $tindakan->NamaTindakan,
            $sesi->session_number,
            Carbon::parse($sesi->scheduled_date)->format('d/m/Y')
        );
        
        // TODO: Implementasi actual notification (email, SMS, WhatsApp, dll)
        // Untuk saat ini, just log atau simpan ke notification table
        \Log::info("Staff Reminder: " . $message);
        
        $reminder->markAsSent();
    }
    
    /**
     * Send reminder ke pasien (Opsional - memerlukan integrasi WhatsApp/Email)
     */
    protected function sendPatientReminder($reminder)
    {
        $sesi = $reminder->sesiTindakan;
        $tindakan = $sesi->tindakanSpesialis;
        $pasien = $tindakan->pasien;
        
        $message = sprintf(
            "REMINDER %s:\nHalo %s,\nAnda memiliki jadwal kontrol berkala:\nTindakan: %s\nTanggal: %s\nDokter: %s\n\nTerima kasih.",
            $reminder->reminder_type,
            $pasien->Nama,
            $tindakan->NamaTindakan,
            Carbon::parse($sesi->scheduled_date)->format('d/m/Y H:i'),
            $tindakan->dokter->Nama
        );
        
        // TODO: Integrasi WhatsApp API atau Email
        // Contoh: $this->whatsappService->send($pasien->NoTelp, $message);
        // Contoh: Mail::to($pasien->user->email)->send(new ReminderMail($message));
        
        \Log::info("Patient Reminder: " . $message);
        
        $reminder->markAsSent();
    }
    
    /**
     * Get pending reminders untuk hari ini
     */
    public function getTodayReminders()
    {
        return Reminder::today()->pending()->with('sesiTindakan.tindakanSpesialis.pasien')->get();
    }
    
    /**
     * Get statistics
     */
    public function getStats()
    {
        return [
            'pending' => Reminder::where('is_sent', false)->count(),
            'sent_today' => Reminder::whereDate('sent_at', Carbon::today())->count(),
            'upcoming_sessions' => SesiTindakan::where('status', 'scheduled')
                                               ->whereBetween('scheduled_date', [Carbon::now(), Carbon::now()->addDays(7)])
                                               ->count(),
        ];
    }
}
