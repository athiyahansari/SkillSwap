<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\AvailabilitySlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class TutorAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Guests cannot access availability routes.
     */
    public function test_guest_cannot_access_availability_routes(): void
    {
        $this->get(route('tutor.availability.index'))->assertRedirect('/login');
        $this->post(route('tutor.availability.store'), [])->assertRedirect('/login');
    }

    /**
     * Learners cannot access tutor availability routes.
     */
    public function test_learner_cannot_access_tutor_availability_routes(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $this->actingAs($learner);

        $this->get(route('tutor.availability.index'))
            ->assertRedirect(route('learner.dashboard'));
    }

    /**
     * Tutors can view their availability slots.
     */
    public function test_tutor_can_view_availability_index(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $profile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        
        $slot = AvailabilitySlot::create([
            'tutor_profile_id' => $profile->id,
            'day' => 'Monday',
            'start_time' => '09:00:00',
            'end_time' => '11:00:00',
            'is_available' => true,
        ]);

        $this->actingAs($tutor);

        $response = $this->get(route('tutor.availability.index'));
        $response->assertStatus(200);
        $response->assertSee('Monday');
        $response->assertSee('9:00 AM');
        $response->assertSee('11:00 AM');
    }

    public function test_tutor_can_create_availability_slot(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $profile = TutorProfile::factory()->create(['user_id' => $tutor->id]);

        $this->actingAs($tutor);

        $response = $this->post(route('tutor.availability.store'), [
            'day' => 'Tuesday',
            'start_time' => '13:00',
            'end_time' => '14:00',
        ]);

        $response->assertRedirect(route('tutor.availability.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('availability_slots', [
            'tutor_profile_id' => $profile->id,
            'day' => 'Tuesday',
            'start_time' => '13:00:00',
            'end_time' => '14:00:00',
        ]);
    }

    /**
     * Storing a multi-hour availability range breaks it into 1-hour chunks.
     */
    public function test_availability_slot_is_broken_into_one_hour_chunks(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $profile = TutorProfile::factory()->create(['user_id' => $tutor->id]);

        $this->actingAs($tutor);

        $response = $this->post(route('tutor.availability.store'), [
            'day' => 'Tuesday',
            'start_time' => '09:00',
            'end_time' => '12:00',
        ]);

        $response->assertRedirect(route('tutor.availability.index'));
        $response->assertSessionHas('success');

        // Should create 3 individual slots
        $this->assertEquals(3, AvailabilitySlot::count());

        $this->assertDatabaseHas('availability_slots', [
            'tutor_profile_id' => $profile->id,
            'day' => 'Tuesday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
        ]);

        $this->assertDatabaseHas('availability_slots', [
            'tutor_profile_id' => $profile->id,
            'day' => 'Tuesday',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
        ]);

        $this->assertDatabaseHas('availability_slots', [
            'tutor_profile_id' => $profile->id,
            'day' => 'Tuesday',
            'start_time' => '11:00:00',
            'end_time' => '12:00:00',
        ]);
    }

    /**
     * Tutors cannot create overlapping availability slots on the same day.
     */
    public function test_tutor_cannot_create_overlapping_availability_slot(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $profile = TutorProfile::factory()->create(['user_id' => $tutor->id]);

        AvailabilitySlot::create([
            'tutor_profile_id' => $profile->id,
            'day' => 'Wednesday',
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
        ]);

        $this->actingAs($tutor);

        // Attempting to add an overlapping slot (10:00 - 11:00)
        $response = $this->post(route('tutor.availability.store'), [
            'day' => 'Wednesday',
            'start_time' => '10:00',
            'end_time' => '11:00',
        ]);

        $response->assertSessionHasErrors(['start_time']);
        $this->assertEquals(1, AvailabilitySlot::count());
    }

    /**
     * Tutors cannot create a slot with start_time >= end_time.
     */
    public function test_tutor_cannot_create_slot_with_invalid_times(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $profile = TutorProfile::factory()->create(['user_id' => $tutor->id]);

        $this->actingAs($tutor);

        $response = $this->post(route('tutor.availability.store'), [
            'day' => 'Thursday',
            'start_time' => '15:00',
            'end_time' => '14:00',
        ]);

        $response->assertSessionHasErrors(['end_time']);
        $this->assertEquals(0, AvailabilitySlot::count());
    }

    /**
     * Tutors can delete their own availability slots.
     */
    public function test_tutor_can_delete_own_availability_slot(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $profile = TutorProfile::factory()->create(['user_id' => $tutor->id]);

        $slot = AvailabilitySlot::create([
            'tutor_profile_id' => $profile->id,
            'day' => 'Friday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
        ]);

        $this->actingAs($tutor);

        $response = $this->delete(route('tutor.availability.destroy', $slot));
        $response->assertRedirect(route('tutor.availability.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('availability_slots', [
            'id' => $slot->id,
        ]);
    }

    /**
     * Learners can book a session that falls within the tutor's availability.
     */
    public function test_learner_can_book_tutor_within_availability(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $tutor = User::factory()->create(['role' => 'tutor']);
        $profile = TutorProfile::factory()->create([
            'user_id' => $tutor->id,
            'hourly_rate' => 20.00
        ]);

        $subject = Subject::create(['name' => 'Math', 'slug' => 'math']);
        $profile->subjects()->attach($subject);

        // Setup availability on Friday 10:00 to 12:00
        AvailabilitySlot::create([
            'tutor_profile_id' => $profile->id,
            'day' => 'Friday',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
        ]);

        // Friday next week to ensure it's in the future and always falls on Friday
        $nextFriday = Carbon::now()->next(Carbon::FRIDAY);

        $this->actingAs($learner);

        $response = $this->post(route('learner.bookings.store', $profile), [
            'subject_id' => $subject->id,
            'session_date' => $nextFriday->format('Y-m-d'),
            'session_time' => '10:30',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bookings', [
            'learner_id' => $learner->id,
            'tutor_profile_id' => $profile->id,
            'session_date' => $nextFriday->format('Y-m-d'),
            'session_time' => '10:30:00',
        ]);
    }

    /**
     * Learners cannot book a session that falls outside the tutor's availability.
     */
    public function test_learner_cannot_book_tutor_outside_availability(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $tutor = User::factory()->create(['role' => 'tutor']);
        $profile = TutorProfile::factory()->create([
            'user_id' => $tutor->id,
            'hourly_rate' => 20.00
        ]);

        $subject = Subject::create(['name' => 'Math', 'slug' => 'math']);
        $profile->subjects()->attach($subject);

        // Setup availability on Friday 10:00 to 12:00
        AvailabilitySlot::create([
            'tutor_profile_id' => $profile->id,
            'day' => 'Friday',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
        ]);

        $nextFriday = Carbon::now()->next(Carbon::FRIDAY);

        $this->actingAs($learner);

        // Request 11:30 booking (ends at 12:30 which exceeds slot end of 12:00)
        $response = $this->post(route('learner.bookings.store', $profile), [
            'subject_id' => $subject->id,
            'session_date' => $nextFriday->format('Y-m-d'),
            'session_time' => '11:30',
        ]);

        $response->assertSessionHasErrors(['session_time']);
        $this->assertEquals(0, Booking::count());
    }
}
