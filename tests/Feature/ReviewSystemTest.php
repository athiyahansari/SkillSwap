<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Review;
use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewSystemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Unauthenticated users cannot access review routes.
     */
    public function test_guest_cannot_access_review_routes(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = Subject::create(['name' => 'Math', 'slug' => 'math']);
        
        $booking = Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->subDays(1)->format('Y-m-d'),
            'session_time' => '10:00:00',
            'status' => 'completed',
        ]);

        $this->get(route('learner.reviews.create', $booking))->assertRedirect('/login');
        $this->post(route('learner.reviews.store', $booking), ['rating' => 5])->assertRedirect('/login');
    }

    /**
     * Tutors cannot access review routes.
     */
    public function test_tutor_cannot_access_review_routes(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = Subject::create(['name' => 'Math', 'slug' => 'math']);
        
        $booking = Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->subDays(1)->format('Y-m-d'),
            'session_time' => '10:00:00',
            'status' => 'completed',
        ]);

        $this->actingAs($tutor);

        $this->get(route('learner.reviews.create', $booking))->assertStatus(403);
    }

    /**
     * Learners cannot leave reviews for other learners' bookings.
     */
    public function test_learner_cannot_review_others_booking(): void
    {
        $learner1 = User::factory()->create(['role' => 'learner']);
        $learner2 = User::factory()->create(['role' => 'learner']);
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = Subject::create(['name' => 'Math', 'slug' => 'math']);
        
        $booking = Booking::create([
            'learner_id' => $learner1->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->subDays(1)->format('Y-m-d'),
            'session_time' => '10:00:00',
            'status' => 'completed',
        ]);

        $this->actingAs($learner2);

        $this->get(route('learner.reviews.create', $booking))->assertStatus(403);
        $this->post(route('learner.reviews.store', $booking), [
            'rating' => 4,
            'comment' => 'Nice session',
        ])->assertStatus(403);
    }

    /**
     * Learners cannot review bookings that are not completed.
     */
    public function test_learner_cannot_review_non_completed_booking(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = Subject::create(['name' => 'Math', 'slug' => 'math']);
        
        $booking = Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->addDays(2)->format('Y-m-d'),
            'session_time' => '10:00:00',
            'status' => 'confirmed', // not completed
        ]);

        $this->actingAs($learner);

        // Access form redirects with session error
        $response = $this->get(route('learner.reviews.create', $booking));
        $response->assertRedirect(route('learner.bookings.index'));
        $response->assertSessionHas('error');

        // Post request redirects with session error
        $response = $this->post(route('learner.reviews.store', $booking), [
            'rating' => 5,
        ]);
        $response->assertRedirect(route('learner.bookings.index'));
        $response->assertSessionHas('error');
    }

    /**
     * Learners can submit a valid review for a completed booking.
     */
    public function test_learner_can_submit_valid_review(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = Subject::create(['name' => 'Math', 'slug' => 'math']);
        
        $booking = Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->subDays(1)->format('Y-m-d'),
            'session_time' => '10:00:00',
            'status' => 'completed',
        ]);

        $this->actingAs($learner);

        // Access review creation form
        $this->get(route('learner.reviews.create', $booking))->assertStatus(200);

        // Post valid review
        $response = $this->post(route('learner.reviews.store', $booking), [
            'rating' => 5,
            'comment' => 'Excellent tutor, highly recommended!',
        ]);

        $response->assertRedirect(route('learner.bookings.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'booking_id' => $booking->id,
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'rating' => 5,
            'comment' => 'Excellent tutor, highly recommended!',
        ]);
    }

    /**
     * Duplicate reviews for the same booking are prevented.
     */
    public function test_duplicate_reviews_are_prevented(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = Subject::create(['name' => 'Math', 'slug' => 'math']);
        
        $booking = Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->subDays(1)->format('Y-m-d'),
            'session_time' => '10:00:00',
            'status' => 'completed',
        ]);

        // Pre-create first review
        Review::create([
            'booking_id' => $booking->id,
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'rating' => 4,
        ]);

        $this->actingAs($learner);

        // Try to access create form again
        $response = $this->get(route('learner.reviews.create', $booking));
        $response->assertRedirect(route('learner.bookings.index'));
        $response->assertSessionHas('error');

        // Try to post again
        $response = $this->post(route('learner.reviews.store', $booking), [
            'rating' => 5,
        ]);
        $response->assertRedirect(route('learner.bookings.index'));
        $response->assertSessionHas('error');
        $this->assertEquals(1, Review::count()); // Still only 1 review
    }
}
