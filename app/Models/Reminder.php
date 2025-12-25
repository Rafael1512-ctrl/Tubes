<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $table = 'reminders';
    protected $primaryKey = 'id';

    protected $fillable = [
        'IdSesiTindakan',
        'reminder_type',
        'reminder_date',
        'is_sent',
        'sent_at',
        'recipient_type',
        'recipient_id',
    ];

    protected $dates = [
        'reminder_date',
        'sent_at',
    ];

    protected $casts = [
        'is_sent' => 'boolean',
    ];

    // Relasi
    public function sesiTindakan()
    {
        return $this->belongsTo(SesiTindakan::class, 'IdSesiTindakan', 'id');
    }

    // Mark reminder as sent
    public function markAsSent()
    {
        $this->is_sent = true;
        $this->sent_at = now();
        $this->save();
    }

    // Send reminder (placeholder - implementasi actual via service)
    public function send()
    {
        // Logic untuk send reminder akan dihandle oleh ReminderService
        // Ini just placeholder method
        $this->markAsSent();
        return true;
    }

    // Scope untuk pending reminders
    public function scopePending($query)
    {
        return $query->where('is_sent', false)
                     ->where('reminder_date', '<=', now()->toDateString());
    }

    // Scope untuk reminders hari ini
    public function scopeToday($query)
    {
        return $query->where('reminder_date', now()->toDateString());
    }
}
