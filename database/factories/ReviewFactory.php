<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Review;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory()->completed(),
            'learner_id' => User::factory()->learner(),
            'tutor_profile_id' => TutorProfile::factory(),
            'rating' => fake()->numberBetween(3, 5),
            'comment' => fake()->randomElement([
                'Excellent lesson! The tutor explained everything clearly.',
                'Very patient and structured. I learned a lot today.',
                'Great tutor. Highly recommended for this subject.',
                'Helped me prepare for my exam and I feel much more confident now.',
                'Fantastic teacher! Very engaging and knowledgeable.',
                'Good explanation of complex topics. Will book again.',
            ]),
        ];
    }
}
