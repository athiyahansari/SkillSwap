<?php

namespace Database\Factories;

use App\Models\AvailabilitySlot;
use App\Models\TutorProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AvailabilitySlot>
 */
class AvailabilitySlotFactory extends Factory
{
    protected $model = AvailabilitySlot::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startHour = fake()->numberBetween(8, 17); // 8 AM to 5 PM
        $start_time = sprintf('%02d:00:00', $startHour);
        $end_time = sprintf('%02d:00:00', $startHour + fake()->numberBetween(1, 3)); // 1-3 hours slot duration
        
        return [
            'tutor_profile_id' => TutorProfile::factory(),
            'day' => fake()->randomElement(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'is_available' => true,
        ];
    }
}
