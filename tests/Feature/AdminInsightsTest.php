<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminInsightsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_correct_platform_insights()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $learner = User::factory()->create(['role' => 'learner']);
        $tutorUser = User::factory()->create(['role' => 'tutor']);
        
        $subject = Subject::create(['name' => 'Laravel', 'slug' => 'laravel', 'description' => 'PHP Framework']);
        
        $tutorProfile = TutorProfile::factory()->create([
            'user_id' => $tutorUser->id,
            'hourly_rate' => 50.00,
            'verification_status' => 'verified',
        ]);
        $tutorProfile->subjects()->attach($subject);

        // Create completed booking to test earnings
        Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->addDays(1)->toDateString(),
            'session_time' => '10:00:00',
            'status' => 'completed',
            'hourly_rate' => 50.00,
            'platform_fee' => 5.00,
            'tutor_earnings' => 45.00,
        ]);

        // Create pending booking
        Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->addDays(2)->toDateString(),
            'session_time' => '11:00:00',
            'status' => 'pending',
            'hourly_rate' => 50.00,
            'platform_fee' => 5.00,
            'tutor_earnings' => 45.00,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        
        $response->assertViewHas('totalUsers', 3);
        $response->assertViewHas('totalLearners', 1);
        $response->assertViewHas('totalGuides', 1);
        $response->assertViewHas('completedSessions', 1);
        $response->assertViewHas('pendingBookings', 1);
        $response->assertViewHas('totalPlatformEarnings', 5.00);
        $response->assertViewHas('recentCompletedSessions');
        $response->assertViewHas('topActiveGuides');
    }
}
