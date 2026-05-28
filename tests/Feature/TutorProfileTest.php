<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TutorProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TutorProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Unauthenticated users are redirected to login.
     */
    public function test_unauthenticated_user_cannot_access_profile_routes(): void
    {
        $this->get(route('tutor.profile.show'))->assertRedirect('/login');
        $this->get(route('tutor.profile.create'))->assertRedirect('/login');
        $this->post(route('tutor.profile.store'), [])->assertRedirect('/login');
        $this->get(route('tutor.profile.edit'))->assertRedirect('/login');
        $this->put(route('tutor.profile.update'), [])->assertRedirect('/login');
    }

    /**
     * Learners are forbidden from accessing tutor profile routes.
     */
    public function test_learner_cannot_access_tutor_profile_routes(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $this->actingAs($learner);

        $this->get(route('tutor.profile.show'))->assertRedirect('/learner/dashboard');
        $this->get(route('tutor.profile.create'))->assertRedirect('/learner/dashboard');
        $this->post(route('tutor.profile.store'), [])->assertRedirect('/learner/dashboard');
        $this->get(route('tutor.profile.edit'))->assertRedirect('/learner/dashboard');
        $this->put(route('tutor.profile.update'), [])->assertRedirect('/learner/dashboard');
    }

    /**
     * Tutors without a profile are redirected to the creation form.
     */
    public function test_tutor_without_profile_redirected_to_create(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $this->actingAs($tutor);

        $response = $this->get(route('tutor.profile.show'));

        $response->assertRedirect(route('tutor.profile.create'));
        $response->assertSessionHas('info');
    }

    /**
     * Tutors can create a profile with a photo upload.
     */
    public function test_tutor_can_create_profile(): void
    {
        Storage::fake('public');

        $tutor = User::factory()->create(['role' => 'tutor']);
        $this->actingAs($tutor);

        $photo = UploadedFile::fake()->create('profile.jpg', 100, 'image/jpeg');

        $response = $this->post(route('tutor.profile.store'), [
            'bio' => 'This is a long test bio with at least 20 characters.',
            'hourly_rate' => 29.99,
            'education' => 'Bachelor of Mathematics',
            'experience' => '3 years high school tutoring',
            'profile_photo' => $photo,
        ]);

        $response->assertRedirect(route('tutor.profile.show'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tutor_profiles', [
            'user_id' => $tutor->id,
            'hourly_rate' => '29.99',
            'education' => 'Bachelor of Mathematics',
            'experience' => '3 years high school tutoring',
            'verification_status' => 'pending',
        ]);

        $profile = TutorProfile::where('user_id', $tutor->id)->first();
        $this->assertNotNull($profile->profile_photo);
        Storage::disk('public')->assertExists($profile->profile_photo);
    }

    /**
     * Tutors cannot create duplicate profiles.
     */
    public function test_tutor_cannot_create_duplicate_profile(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $this->actingAs($tutor);

        // Pre-create profile
        TutorProfile::factory()->create([
            'user_id' => $tutor->id,
        ]);

        $response = $this->post(route('tutor.profile.store'), [
            'bio' => 'This is another test bio with at least 20 characters.',
            'hourly_rate' => 35.00,
            'education' => 'Ph.D. in Science',
            'experience' => '10 years tutoring',
        ]);

        $response->assertRedirect(route('tutor.profile.show'));
        $response->assertSessionHas('error');
    }

    /**
     * Tutors can edit and update their profile and replace the photo.
     */
    public function test_tutor_can_edit_and_update_profile(): void
    {
        Storage::fake('public');

        $tutor = User::factory()->create(['role' => 'tutor']);
        $this->actingAs($tutor);

        // Pre-create profile with a mock photo
        $oldPhotoPath = 'tutor_photos/old.jpg';
        Storage::disk('public')->put($oldPhotoPath, 'old photo contents');

        $profile = TutorProfile::factory()->create([
            'user_id' => $tutor->id,
            'profile_photo' => $oldPhotoPath,
        ]);

        // Access edit form
        $this->get(route('tutor.profile.edit'))
            ->assertStatus(200)
            ->assertViewHas('profile');

        // Update profile with a new photo
        $newPhoto = UploadedFile::fake()->create('new_profile.png', 100, 'image/png');

        $response = $this->put(route('tutor.profile.update'), [
            'bio' => 'This is an updated biography with 20+ characters.',
            'hourly_rate' => 45.00,
            'education' => 'Master of Physics',
            'experience' => '5 years tutoring university students',
            'profile_photo' => $newPhoto,
        ]);

        $response->assertRedirect(route('tutor.profile.show'));
        $response->assertSessionHas('success');

        // Check DB update
        $this->assertDatabaseHas('tutor_profiles', [
            'id' => $profile->id,
            'hourly_rate' => '45.00',
            'education' => 'Master of Physics',
            'experience' => '5 years tutoring university students',
        ]);

        // Check files on disk
        Storage::disk('public')->assertMissing($oldPhotoPath); // Old photo deleted
        $updatedProfile = $profile->fresh();
        Storage::disk('public')->assertExists($updatedProfile->profile_photo); // New photo created
    }
}
