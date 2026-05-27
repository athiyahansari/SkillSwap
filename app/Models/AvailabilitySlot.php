<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailabilitySlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_profile_id',
        'day',
        'start_time',
        'end_time',
        'is_available',
    ];

    public function tutorProfile()
    {
        return $this->belongsTo(TutorProfile::class);
    }
}