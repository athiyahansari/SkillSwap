<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tutorProfile()
    {
        return $this->hasOne(TutorProfile::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'learner_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'learner_id');
    }

    public function conversationsAsLearner()
    {
        return $this->hasMany(Conversation::class, 'learner_id');
    }

    public function conversationsAsTutor()
    {
        return $this->hasMany(Conversation::class, 'tutor_id');
    }

    public function unreadMessagesCount()
    {
        return Message::whereHas('conversation', function ($query) {
            $query->where('learner_id', $this->id)
                  ->orWhere('tutor_id', $this->id);
        })->where('sender_id', '!=', $this->id)->where('is_read', false)->count();
    }

    /**
     * Get the redirect URL for the user's dashboard based on their role.
     */
    public function dashboardUrl(): string
    {
        return match ($this->role) {
            'admin' => '/admin/dashboard',
            'tutor' => '/tutor/dashboard',
            default => '/learner/dashboard',
        };
    }
}
