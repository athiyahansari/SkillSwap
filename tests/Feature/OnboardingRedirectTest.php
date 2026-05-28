<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_learner_sees_onboarding_data(): void
    {
        $user = User::factory()->create([
            'role' => 'learner',
            'email_verified_at' => now(),
            'created_at' => now(), // Created just now = new user
        ]);

        $response = $this->actingAs($user)->get(route('learner.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('isNewUser', true);
        $response->assertViewHas('emailVerified', true);
    }

    public function test_existing_learner_not_new_user(): void
    {
        $user = User::factory()->create([
            'role' => 'learner',
            'email_verified_at' => now(),
            'created_at' => now()->subDays(30), // Old user
        ]);

        $response = $this->actingAs($user)->get(route('learner.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('isNewUser', false);
    }

    public function test_unverified_learner_dashboard_shows_verification_status(): void
    {
        $user = User::factory()->create([
            'role' => 'learner',
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('learner.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('emailVerified', false);
    }

    public function test_tutor_dashboard_shows_onboarding_data(): void
    {
        $user = User::factory()->create([
            'role' => 'tutor',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('tutor.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('onboarding');
        $response->assertViewHas('profileComplete', false);
        $response->assertViewHas('completionPercent', 0);
        $response->assertViewHas('emailVerified', true);
    }

    public function test_role_based_redirect_after_login(): void
    {
        $learner = User::factory()->create([
            'role' => 'learner',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($learner)->get('/dashboard');

        $response->assertRedirect('/learner/dashboard');
    }

    public function test_tutor_role_redirect_after_login(): void
    {
        $tutor = User::factory()->create([
            'role' => 'tutor',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($tutor)->get('/dashboard');

        $response->assertRedirect('/tutor/dashboard');
    }

    public function test_wrong_role_redirects_to_own_dashboard(): void
    {
        $learner = User::factory()->create([
            'role' => 'learner',
            'email_verified_at' => now(),
        ]);

        // Learner tries to access tutor dashboard
        $response = $this->actingAs($learner)->get(route('tutor.dashboard'));

        $response->assertRedirect('/learner/dashboard');
        $response->assertSessionHas('error');
    }

    public function test_unverified_user_cannot_book_session(): void
    {
        $user = User::factory()->create([
            'role' => 'learner',
            'email_verified_at' => null,
        ]);

        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = \App\Models\TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = \App\Models\Subject::create(['name' => 'Test Subject', 'slug' => 'test-subject', 'description' => 'Test']);
        $tutorProfile->subjects()->attach($subject);

        // Try to access a booking route which requires verified middleware
        $response = $this->actingAs($user)->post(route('learner.bookings.store', $tutorProfile), [
            'subject_id' => $subject->id,
            'session_date' => now()->addDay()->toDateString(),
            'session_time' => '10:00',
        ]);

        $response->assertRedirect(route('verification.notice'));
    }
}
