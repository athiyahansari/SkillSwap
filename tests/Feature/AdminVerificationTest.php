<?php

namespace Tests\Feature;

use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Unauthenticated users cannot access verification routes.
     */
    public function test_guest_cannot_access_verification_routes(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);

        $this->put(route('admin.tutors.verify', $tutorProfile))->assertRedirect('/login');
        $this->put(route('admin.tutors.reject', $tutorProfile))->assertRedirect('/login');
        $this->put(route('admin.tutors.revert', $tutorProfile))->assertRedirect('/login');
    }

    /**
     * Learners cannot access verification routes.
     */
    public function test_learner_cannot_access_verification_routes(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);

        $this->actingAs($learner);

        $this->put(route('admin.tutors.verify', $tutorProfile))->assertRedirect('/learner/dashboard');
        $this->put(route('admin.tutors.reject', $tutorProfile))->assertRedirect('/learner/dashboard');
        $this->put(route('admin.tutors.revert', $tutorProfile))->assertRedirect('/learner/dashboard');
    }

    /**
     * Tutors cannot access verification routes.
     */
    public function test_tutor_cannot_access_verification_routes(): void
    {
        $tutor1 = User::factory()->create(['role' => 'tutor']);
        $tutor2 = User::factory()->create(['role' => 'tutor']);
        $tutorProfile2 = TutorProfile::factory()->create(['user_id' => $tutor2->id]);

        $this->actingAs($tutor1);

        $this->put(route('admin.tutors.verify', $tutorProfile2))->assertRedirect('/tutor/dashboard');
    }

    /**
     * Admins can view pending tutor applications and verify/reject/revert them.
     */
    public function test_admin_can_manage_tutor_verification(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create([
            'user_id' => $tutor->id,
            'verification_status' => 'pending',
        ]);

        $this->actingAs($admin);

        // Access dashboard
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee($tutor->name);

        // Verify tutor
        $response = $this->put(route('admin.tutors.verify', $tutorProfile));
        $response->assertSessionHas('success');
        $this->assertEquals('verified', $tutorProfile->fresh()->verification_status);

        // Revert to pending
        $response = $this->put(route('admin.tutors.revert', $tutorProfile));
        $response->assertSessionHas('success');
        $this->assertEquals('pending', $tutorProfile->fresh()->verification_status);

        // Reject tutor
        $response = $this->put(route('admin.tutors.reject', $tutorProfile));
        $response->assertSessionHas('success');
        $this->assertEquals('rejected', $tutorProfile->fresh()->verification_status);
    }
}
