<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Subject;
use App\Models\TutorProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Public listing is accessible by guests without logging in.
     */
    public function test_public_user_can_access_tutor_listing(): void
    {
        $response = $this->get(route('tutors.index'));
        $response->assertStatus(200);
        $response->assertViewIs('tutors.index');
    }

    /**
     * Tutors with completed profiles appear in listings, but empty profiles are omitted.
     */
    public function test_only_completed_tutor_profiles_appear_in_listings(): void
    {
        // 1. Tutor with completed profile
        $tutorCompleted = User::factory()->create(['role' => 'tutor']);
        TutorProfile::factory()->create([
            'user_id' => $tutorCompleted->id,
            'bio' => 'This is a completed tutor biography with sufficient characters.',
            'hourly_rate' => 35.00,
            'profile_photo' => 'tutor_photos/photo1.jpg',
        ]);

        // 2. Tutor with empty/incomplete profile
        $tutorIncomplete = User::factory()->create(['role' => 'tutor']);
        TutorProfile::create([
            'user_id' => $tutorIncomplete->id,
            'bio' => null,
            'hourly_rate' => null,
            'profile_photo' => null,
        ]);

        $response = $this->get(route('tutors.index'));

        $response->assertSee($tutorCompleted->name);
        $response->assertDontSee($tutorIncomplete->name);
    }

    /**
     * Public users can view a completed tutor profile.
     */
    public function test_public_user_can_view_tutor_profile(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $profile = TutorProfile::factory()->create([
            'user_id' => $tutor->id,
            'bio' => 'A completed biography details page for public testing.',
            'hourly_rate' => 45.00,
            'profile_photo' => 'tutor_photos/photo2.jpg',
        ]);

        $response = $this->get(route('tutors.show', $profile->id));

        $response->assertStatus(200);
        $response->assertViewIs('tutors.show');
        $response->assertSee($tutor->name);
        $response->assertSee('A completed biography details page');
    }

    /**
     * Tutors can access their subjects edit page.
     */
    public function test_tutor_can_access_subjects_edit_page(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $this->actingAs($tutor);

        $response = $this->get(route('tutor.subjects.edit'));
        $response->assertStatus(200);
        $response->assertViewHas('allSubjects');
    }

    /**
     * Tutors can select and sync subjects, and sync prevents duplicate links.
     */
    public function test_tutor_can_sync_subjects_without_duplicates(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $profile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $this->actingAs($tutor);

        $subject1 = Subject::create(['slug' => 'math', 'name' => 'Math']);
        $subject2 = Subject::create(['slug' => 'sci', 'name' => 'Science']);

        // Check clean list initially
        $this->assertCount(0, $profile->subjects);

        // Sync subjects
        $response = $this->put(route('tutor.subjects.update'), [
            'subjects' => [$subject1->id, $subject2->id]
        ]);

        $response->assertRedirect(route('tutor.profile.show'));
        $this->assertCount(2, $profile->fresh()->subjects);

        // Attempting to sync again with same values does not cause duplicates
        $this->put(route('tutor.subjects.update'), [
            'subjects' => [$subject1->id, $subject2->id]
        ]);

        $this->assertCount(2, $profile->fresh()->subjects);
    }
}
