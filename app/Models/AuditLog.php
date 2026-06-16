<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class AuditLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'audit_logs';

    protected $fillable = [
        'event_type',
        'model_type',
        'model_id',
        'user_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];
}
