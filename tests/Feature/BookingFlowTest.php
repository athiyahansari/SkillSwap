<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Guest cannot access booking routes or request bookings.
     */
    public function test_guest_cannot_request_booking(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = Subject::create(['name' => 'Chemistry', 'slug' => 'chemistry']);

        $this->post(route('learner.bookings.store', $tutorProfile), [
            'subject_id' => $subject->id,
            'session_date' => now()->addDays(2)->format('Y-m-d'),
            'session_time' => '14:00',
        ])->assertRedirect('/login');

        $this->get(route('learner.bookings.index'))->assertRedirect('/login');
    }

    /**
     * Tutors cannot request lesson bookings.
     */
    public function test_tutor_cannot_request_booking(): void
    {
        $tutor1 = User::factory()->create(['role' => 'tutor']);
        $tutor2 = User::factory()->create(['role' => 'tutor']);
        $tutorProfile2 = TutorProfile::factory()->create(['user_id' => $tutor2->id]);
        $subject = Subject::create(['name' => 'Chemistry', 'slug' => 'chemistry']);
        $tutorProfile2->subjects()->attach($subject);

        $this->actingAs($tutor1);

        $this->post(route('learner.bookings.store', $tutorProfile2), [
            'subject_id' => $subject->id,
            'session_date' => now()->addDays(2)->format('Y-m-d'),
            'session_time' => '14:00',
        ])->assertRedirect();
    }

    /**
     * Learners can request bookings with valid details.
     */
    public function test_learner_can_request_booking(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = Subject::create(['name' => 'Physics', 'slug' => 'physics']);
        $tutorProfile->subjects()->attach($subject);

        $learner = User::factory()->create(['role' => 'learner']);
        $this->actingAs($learner);

        $date = now()->addDays(2)->format('Y-m-d');
        $time = '15:30';

        $response = $this->from(route('tutors.show', $tutorProfile))
            ->post(route('learner.bookings.store', $tutorProfile), [
                'subject_id' => $subject->id,
                'session_date' => $date,
                'session_time' => $time,
                'notes' => 'I need help with quantum mechanics equations.',
            ]);

        $response->assertRedirect(route('tutors.show', $tutorProfile));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => $date,
            'session_time' => '15:30:00',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'notes' => 'I need help with quantum mechanics equations.',
        ]);
    }

    /**
     * Validation prevents choosing a subject the tutor doesn't teach.
     */
    public function test_cannot_book_subject_tutor_does_not_teach(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        
        $subject1 = Subject::create(['name' => 'French', 'slug' => 'french']);
        $subject2 = Subject::create(['name' => 'Spanish', 'slug' => 'spanish']);
        
        $tutorProfile->subjects()->attach($subject1);

        $learner = User::factory()->create(['role' => 'learner']);
        $this->actingAs($learner);

        $response = $this->post(route('learner.bookings.store', $tutorProfile), [
            'subject_id' => $subject2->id,
            'session_date' => now()->addDays(2)->format('Y-m-d'),
            'session_time' => '10:00',
        ]);

        $response->assertSessionHasErrors(['subject_id']);
        $this->assertDatabaseMissing('bookings', ['learner_id' => $learner->id]);
    }

    /**
     * Validation prevents duplicate (conflicting) bookings for the same tutor.
     */
    public function test_booking_conflict_is_prevented(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = Subject::create(['name' => 'Algebra', 'slug' => 'algebra']);
        $tutorProfile->subjects()->attach($subject);

        $learner1 = User::factory()->create(['role' => 'learner']);
        $learner2 = User::factory()->create(['role' => 'learner']);

        $date = now()->addDays(3)->format('Y-m-d');
        $time = '11:00';

        // Pre-create standard pending booking for tutor
        Booking::create([
            'learner_id' => $learner1->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => $date,
            'session_time' => '11:00:00',
            'status' => 'pending',
        ]);

        // Try to book the same slot
        $this->actingAs($learner2);
        $response = $this->post(route('learner.bookings.store', $tutorProfile), [
            'subject_id' => $subject->id,
            'session_date' => $date,
            'session_time' => $time,
        ]);

        $response->assertSessionHasErrors(['session_time']);
        $this->assertEquals(1, Booking::count()); // Still only 1 booking in database
    }

    /**
     * Learners can cancel their pending bookings.
     */
    public function test_learner_can_cancel_pending_booking(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = Subject::create(['name' => 'History', 'slug' => 'history']);
        
        $learner = User::factory()->create(['role' => 'learner']);
        $this->actingAs($learner);

        $booking = Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->addDays(5)->format('Y-m-d'),
            'session_time' => '09:00:00',
            'status' => 'pending',
        ]);

        $response = $this->put(route('learner.bookings.cancel', $booking));
        $response->assertSessionHas('success');
        $this->assertEquals('cancelled', $booking->fresh()->status);
    }

    /**
     * Learners cannot cancel other learners' bookings.
     */
    public function test_learner_cannot_cancel_others_booking(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = Subject::create(['name' => 'History', 'slug' => 'history']);
        
        $learner1 = User::factory()->create(['role' => 'learner']);
        $learner2 = User::factory()->create(['role' => 'learner']);

        $booking = Booking::create([
            'learner_id' => $learner1->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->addDays(5)->format('Y-m-d'),
            'session_time' => '09:00:00',
            'status' => 'pending',
        ]);

        $this->actingAs($learner2);
        $response = $this->put(route('learner.bookings.cancel', $booking));
        $response->assertStatus(403);
        $this->assertEquals('pending', $booking->fresh()->status);
    }

    /**
     * Tutors can view, accept, reject, and complete bookings.
     */
    public function test_tutor_can_manage_bookings(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = Subject::create(['name' => 'History', 'slug' => 'history']);
        $tutorProfile->subjects()->attach($subject);

        $learner = User::factory()->create(['role' => 'learner']);

        $booking = Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->addDays(5)->format('Y-m-d'),
            'session_time' => '09:00:00',
            'status' => 'pending',
        ]);

        $this->actingAs($tutor);

        // Accept
        $response = $this->put(route('tutor.bookings.accept', $booking));
        $response->assertSessionHas('success');
        $this->assertEquals('confirmed', $booking->fresh()->status);

        // Reset to pending to test reject
        $booking = $booking->fresh();
        $booking->update(['status' => 'pending']);
        $response = $this->put(route('tutor.bookings.reject', $booking));
        $response->assertSessionHas('success');
        $this->assertEquals('cancelled', $booking->fresh()->status);

        // Set to confirmed to test complete
        $booking->update(['status' => 'confirmed']);
        $response = $this->put(route('tutor.bookings.complete', $booking));
        $response->assertSessionHas('success');
        $this->assertEquals('completed', $booking->fresh()->status);
    }

    /**
     * Tutors cannot manage other tutors' bookings.
     */
    public function test_tutor_cannot_manage_others_bookings(): void
    {
        $tutor1 = User::factory()->create(['role' => 'tutor']);
        $tutorProfile1 = TutorProfile::factory()->create(['user_id' => $tutor1->id]);

        $tutor2 = User::factory()->create(['role' => 'tutor']);
        $tutorProfile2 = TutorProfile::factory()->create(['user_id' => $tutor2->id]);

        $subject = Subject::create(['name' => 'History', 'slug' => 'history']);
        $learner = User::factory()->create(['role' => 'learner']);

        $booking = Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile1->id,
            'subject_id' => $subject->id,
            'session_date' => now()->addDays(5)->format('Y-m-d'),
            'session_time' => '09:00:00',
            'status' => 'pending',
        ]);

        $this->actingAs($tutor2);

        // Accept attempt by Tutor 2 should fail
        $response = $this->put(route('tutor.bookings.accept', $booking));
        $response->assertStatus(403);
        $this->assertEquals('pending', $booking->fresh()->status);
    }

    /**
     * Learners can pay for a confirmed booking.
     */
    public function test_learner_can_pay_for_confirmed_booking(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = Subject::create(['name' => 'Math', 'slug' => 'math']);
        
        $learner = User::factory()->create(['role' => 'learner']);
        $this->actingAs($learner);

        $booking = Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->addDays(5)->format('Y-m-d'),
            'session_time' => '10:00:00',
            'status' => 'confirmed',
            'payment_status' => 'unpaid',
            'hourly_rate' => 20,
            'platform_fee' => 2,
            'tutor_earnings' => 18,
        ]);

        $response = $this->post(route('learner.bookings.pay', $booking));
        $response->assertRedirect('https://checkout.stripe.com/test');
    }

    /**
     * Learners cannot pay for a pending booking.
     */
    public function test_learner_cannot_pay_for_pending_booking(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = Subject::create(['name' => 'Math', 'slug' => 'math']);
        
        $learner = User::factory()->create(['role' => 'learner']);
        $this->actingAs($learner);

        $booking = Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->addDays(5)->format('Y-m-d'),
            'session_time' => '10:00:00',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'hourly_rate' => 20,
            'platform_fee' => 2,
            'tutor_earnings' => 18,
        ]);

        $response = $this->post(route('learner.bookings.pay', $booking));
        $response->assertSessionHas('error');
    }
}
