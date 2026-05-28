<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EarningsCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_calculates_platform_fee_and_earnings()
    {
        $learner = User::factory()->create(['role' => 'learner', 'email_verified_at' => now()]);
        $tutorUser = User::factory()->create(['role' => 'tutor']);
        
        $subject = Subject::create(['name' => 'React', 'slug' => 'react', 'description' => 'JS Library']);
        
        $tutorProfile = TutorProfile::factory()->create([
            'user_id' => $tutorUser->id,
            'hourly_rate' => 100.00,
            'verification_status' => 'verified',
        ]);
        $tutorProfile->subjects()->attach($subject);

        $response = $this->actingAs($learner)->post(route('learner.bookings.store', $tutorProfile), [
            'subject_id' => $subject->id,
            'session_date' => now()->addDays(3)->toDateString(),
            'session_time' => '14:00',
            'notes' => 'Test notes',
        ]);

        $response->assertRedirect('https://checkout.stripe.com/test');

        $this->assertDatabaseHas('bookings', [
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'hourly_rate' => 100.00,
            'platform_fee' => 10.00,
            'tutor_earnings' => 90.00,
            'status' => 'pending',
        ]);
    }
}
