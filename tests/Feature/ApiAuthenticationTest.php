<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TutorProfile;
use App\Models\Subject;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_tutors_api_is_accessible()
    {
        $response = $this->getJson('/api/tutors');

        $response->assertStatus(200);
    }

    public function test_protected_profile_api_requires_authentication()
    {
        $response = $this->getJson('/api/user/profile');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_access_profile_api()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user/profile');

        $response->assertStatus(200)
                 ->assertJsonPath('data.email', $user->email);
    }

    public function test_learner_can_access_learner_bookings_api()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        Sanctum::actingAs($learner);

        $response = $this->getJson('/api/learner/bookings');

        $response->assertStatus(200);
    }

    public function test_tutor_cannot_access_learner_bookings_api()
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        Sanctum::actingAs($tutor);

        $response = $this->getJson('/api/learner/bookings');

        $response->assertStatus(403);
    }

    public function test_tutor_can_access_tutor_earnings_api()
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        TutorProfile::factory()->create(['user_id' => $tutor->id]);
        Sanctum::actingAs($tutor);

        $response = $this->getJson('/api/tutor/earnings');

        $response->assertStatus(200);
    }

    public function test_learner_cannot_access_tutor_earnings_api()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        Sanctum::actingAs($learner);

        $response = $this->getJson('/api/tutor/earnings');

        $response->assertStatus(403);
    }
}
