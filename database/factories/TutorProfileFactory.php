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
            'user_id' => User::factory()->tutor(),
            'bio' => fake()->paragraph(3),
            'hourly_rate' => fake()->randomFloat(2, 20, 75),
            'education' => fake()->randomElement([
                'M.Sc. in Computer Science from Stanford University',
                'Ph.D. in Mathematics from MIT',
                'B.A. in English Literature from Oxford University',
                'M.Sc. in Physics from University of Cambridge',
                'B.Ed. in Secondary Education from Boston University',
                'Certified Web Development Instructor (W3C)',
            ]),
            'experience' => fake()->randomElement([
                '5 years of private tutoring & high school teaching',
                '3 years of university teaching assistant experience',
                '10+ years of professional software engineering and mentoring',
                '4 years of online chemistry tutoring',
                '2 years as an ESL teacher in Japan',
            ]),
            'verification_status' => fake()->randomElement(['verified', 'verified', 'pending', 'rejected']),
            'profile_photo' => null,
        ];
    }

    /**
     * Indicate that the tutor profile is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => 'verified',
        ]);
    }

    /**
     * Indicate that the tutor profile is pending verification.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => 'pending',
        ]);
    }

    /**
     * Indicate that the tutor profile is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => 'rejected',
        ]);
    }
}
