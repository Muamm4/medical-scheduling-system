<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'doctor_name',
        'specialty',
        'appointment_datetime',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'appointment_datetime' => 'datetime',
        'status' => AppointmentStatus::class,
    ];

    /**
     * Get the patient that owns the appointment.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Check if the appointment can be canceled.
     * 
     * @return bool
     */
    public function canBeCanceled(): bool
    {
        if ($this->status !== AppointmentStatus::SCHEDULED) {
            return false;
        }
        
        // Check if appointment is at least 12 hours in the future
        return Carbon::now()->diffInHours($this->appointment_datetime) >= 12;
    }
}
