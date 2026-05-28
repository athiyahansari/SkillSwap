<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'learner_id',
        'tutor_profile_id',
        'subject_id',
        'session_date',
        'session_time',
        'status',
        'notes',
        'hourly_rate',
        'platform_fee',
        'tutor_earnings',
        'payment_status',
        'stripe_session_id',
        'paid_at',
    ];

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function tutorProfile()
    {
        return $this->belongsTo(TutorProfile::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}