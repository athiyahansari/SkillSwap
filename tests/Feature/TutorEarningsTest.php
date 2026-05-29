<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TutorEarningsTest extends TestCase
{
    use RefreshDatabase;

    public function test_tutor_can_view_earnings_page()
    {
        $tutorUser = User::factory()->create(['role' => 'tutor']);
        $learner = User::factory()->create(['role' => 'learner']);
        
        $subject = Subject::create(['name' => 'Python', 'slug' => 'python', 'description' => 'Programming']);
        
        $tutorProfile = TutorProfile::factory()->create([
            'user_id' => $tutorUser->id,
            'hourly_rate' => 80.00,
            'verification_status' => 'verified',
        ]);
        $tutorProfile->subjects()->attach($subject);

        Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->subDays(2)->toDateString(),
            'session_time' => '15:00:00',
            'status' => 'completed',
            'hourly_rate' => 80.00,
            'platform_fee' => 8.00,
            'tutor_earnings' => 72.00,
        ]);

        $response = $this->actingAs($tutorUser)->get(route('tutor.earnings.index'));
        $response->assertStatus(200);
        $response->assertSee('My Earnings');

        \Livewire\Livewire::test(\App\Livewire\TutorEarnings::class)
            ->assertViewHas('totalEarnings', 72.00)
            ->assertViewHas('totalSessions', 1)
            ->assertViewHas('chartData')
            ->assertViewHas('chartLabels')
            ->assertViewHas('recentEarnings');
    }

    public function test_tutor_without_profile_redirected()
    {
        $tutorUser = User::factory()->create(['role' => 'tutor']);
        $response = $this->actingAs($tutorUser)->get(route('tutor.earnings.index'));
        
        $response->assertRedirect(route('tutor.profile.create'));
    }
}
