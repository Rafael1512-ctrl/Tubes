<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SesiTindakan extends Model
{
    protected $table = 'sesi_tindakan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'IdTindakanSpesialis',
        'session_number',
        'scheduled_date',
        'actual_date',
        'status',
        'notes',
        'reschedule_reason',
    ];

    protected $dates = [
        'scheduled_date',
        'actual_date',
    ];

    // Relasi
    public function tindakanSpesialis()
    {
        return $this->belongsTo(TindakanSpesialis::class, 'IdTindakanSpesialis', 'IdTindakanSpesialis');
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class, 'IdSesiTindakan', 'id');
    }

    // Reschedule session
    public function reschedule($newDate, $reason)
    {
        $this->scheduled_date = Carbon::parse($newDate);
        $this->reschedule_reason = $reason;
        $this->status = 'rescheduled';
        $this->save();

        // Hapus reminders lama dan generate yang baru
        $this->reminders()->delete();
        $this->generateReminders();

        return $this;
    }

    // Mark as attended
    public function markAsAttended($notes = null)
    {
        $this->actual_date = Carbon::now();
        $this->status = 'attended';
        $this->notes = $notes;
        $this->save();

        // Update completed sessions count
        $this->tindakanSpesialis->updateCompletedSessions();

        return $this;
    }

    // Mark as completed
    public function markAsCompleted($notes = null)
    {
        $this->actual_date = Carbon::now();
        $this->status = 'completed';
        $this->notes = $notes;
        $this->save();

        // Update completed sessions count
        $this->tindakanSpesialis->updateCompletedSessions();

        return $this;
    }

    // Cancel session
    public function cancel($reason)
    {
        $this->status = 'cancelled';
        $this->reschedule_reason = $reason;
        $this->save();

        // Hapus reminders
        $this->reminders()->delete();

        return $this;
    }

    // Generate reminders untuk sesi ini
    public function generateReminders()
    {
        $scheduledDate = Carbon::parse($this->scheduled_date);
        
        // H-3 reminder
        $h3Date = $scheduledDate->copy()->subDays(3);
        if ($h3Date->isFuture()) {
            Reminder::create([
                'IdSesiTindakan' => $this->id,
                'reminder_type' => 'H-3',
                'reminder_date' => $h3Date->toDateString(),
                'recipient_type' => 'staff',
            ]);
        }
        
        // H-1 reminder
        $h1Date = $scheduledDate->copy()->subDay();
        if ($h1Date->isFuture()) {
            Reminder::create([
                'IdSesiTindakan' => $this->id,
                'reminder_type' => 'H-1',
                'reminder_date' => $h1Date->toDateString(),
                'recipient_type' => 'staff',
            ]);
        }
    }
}
