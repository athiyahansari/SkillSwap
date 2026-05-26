<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'learner_id' => User::factory()->create(['role' => 'learner']),
            'tutor_profile_id' => TutorProfile::factory(),
            'subject_id' => fn() => Subject::first()?->id ?? Subject::create(['slug' => 'math-' . fake()->unique()->word(), 'name' => 'Mathematics'])->id,
            'session_date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'session_time' => '10:00:00',
            'status' => 'pending',
            'notes' => 'Looking forward to our lesson!',
        ];
    }
}
