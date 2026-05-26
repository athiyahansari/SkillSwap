<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function tutorProfiles()
    {
        return $this->belongsToMany(TutorProfile::class, 'tutor_subject');
    }
}