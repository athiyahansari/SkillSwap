<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'booking_id',
        'learner_id',
        'tutor_profile_id',
        'rating',
        'comment',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function tutorProfile()
    {
        return $this->belongsTo(TutorProfile::class);
    }
}