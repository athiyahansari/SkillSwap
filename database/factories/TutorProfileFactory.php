<?php

namespace Database\Factories;

use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TutorProfile>
 */
class TutorProfileFactory extends Factory
{
    protected $model = TutorProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'bio' => 'This is a default dummy bio for a tutor profile factory with enough character length.',
            'hourly_rate' => 25.00,
            'education' => 'Bachelor of Education',
            'experience' => '2 years teaching experience',
            'verification_status' => 'pending',
            'profile_photo' => null,
        ];
    }
}
