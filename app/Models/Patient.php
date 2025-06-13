<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'cpf',
        'birth_date',
        'zip_code',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Get the responsibles for the patient.
     */
    public function responsibles(): HasMany
    {
        return $this->hasMany(Responsible::class);
    }

    /**
     * Get the appointments for the patient.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get active appointments count for the patient.
     * 
     * @return int
     */
    public function activeAppointmentsCount(): int
    {
        return $this->appointments()->where('status', 'scheduled')->count();
    }

    /**
     * Get the patient's age based on birth date.
     * 
     * @return int
     */
    public function getAgeAttribute(): int
    {
        return $this->birth_date->diffInYears(now());
    }
}
