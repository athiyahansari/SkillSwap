<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProfileView extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'profile_views';

    protected $fillable = [
        'tutor_profile_id',
        'ip_address',
        'user_agent',
        'viewed_at',
    ];
}
