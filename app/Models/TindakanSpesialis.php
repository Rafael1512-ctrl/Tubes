<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TindakanSpesialis extends Model
{
    protected $table = 'tindakan_spesialis';
    protected $primaryKey = 'IdTindakanSpesialis';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'IdTindakanSpesialis',
        'PasienID',
        'DokterID',
        'IdRekamMedis',
        'NamaTindakan',
        'is_periodic',
        'frequency',
        'custom_days',
        'total_sessions',
        'completed_sessions',
        'plan_goal',
        'start_date',
        'status',
    ];

    protected $dates = [
        'start_date',
    ];

    protected $casts = [
        'is_periodic' => 'boolean',
    ];

    // Relasi
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'PasienID', 'PasienID');
    }

    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'DokterID', 'PegawaiID');
    }

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'IdRekamMedis', 'IdRekamMedis');
    }

    public function sessions()
    {
        return $this->hasMany(SesiTindakan::class, 'IdTindakanSpesialis', 'IdTindakanSpesialis');
    }

    // Generate sessions otomatis berdasarkan frequency
    public function generateSessions()
    {
        // Hapus sesi lama jika ada
        $this->sessions()->delete();

        $sessionDates = [];
        $currentDate = Carbon::parse($this->start_date);

        for ($i = 1; $i <= $this->total_sessions; $i++) {
            $sessionDates[] = [
                'IdTindakanSpesialis' => $this->IdTindakanSpesialis,
                'session_number' => $i,
                'scheduled_date' => $currentDate->toDateString(),
                'status' => 'scheduled',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Calculate next session date
            if ($i < $this->total_sessions) {
                switch ($this->frequency) {
                    case 'weekly':
                        $currentDate->addWeek();
                        break;
                    case 'monthly':
                        $currentDate->addMonth();
                        break;
                    case 'custom':
                        $currentDate->addDays($this->custom_days ?? 7);
                        break;
                }
            }
        }

        SesiTindakan::insert($sessionDates);
        
        return $this->sessions;
    }

    // Get progress percentage
    public function getProgressPercentage()
    {
        if ($this->total_sessions == 0) {
            return 0;
        }
        return round(($this->completed_sessions / $this->total_sessions) * 100);
    }

    // Get next session
    public function getNextSession()
    {
        return $this->sessions()
                    ->where('status', 'scheduled')
                    ->where('scheduled_date', '>=', Carbon::now()->toDateString())
                    ->orderBy('scheduled_date')
                    ->first();
    }

    // Update completed sessions count
    public function updateCompletedSessions()
    {
        $completed = $this->sessions()->whereIn('status', ['attended', 'completed'])->count();
        $this->completed_sessions = $completed;
        
        // Auto-complete tindakan jika semua sesi selesai
        if ($completed >= $this->total_sessions) {
            $this->status = 'completed';
        }
        
        $this->save();
    }
}
