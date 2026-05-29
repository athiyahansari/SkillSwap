<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\TutorProfile;
use App\Models\Subject;
use App\Models\Booking;
use Livewire\Livewire;
use App\Livewire\NotificationsDropdown;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_confirmation_sends_notification()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $tutorUser = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutorUser->id]);
        $subject = Subject::factory()->create();

        $booking = Booking::factory()->create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($tutorUser)->put(route('tutor.bookings.accept', $booking));
        $response->assertSessionHas('success');

        $this->assertEquals(1, $learner->notifications()->count());
        $this->assertEquals('booking_confirmed', $learner->notifications()->first()->data['type']);
    }

    public function test_livewire_dropdown_renders_and_marks_as_read()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $tutorUser = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutorUser->id]);
        $subject = Subject::factory()->create();

        $booking = Booking::factory()->create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'status' => 'pending'
        ]);

        // Tutor accepts booking, sending a notification to the learner
        $this->actingAs($tutorUser)->put(route('tutor.bookings.accept', $booking));
        
        $this->assertEquals(1, $learner->unreadNotifications()->count());

        $notificationId = $learner->unreadNotifications()->first()->id;

        Livewire::actingAs($learner)
            ->test(NotificationsDropdown::class)
            ->assertSee('has been confirmed') // From the message string
            ->call('markAsRead', $notificationId);

        $this->assertEquals(0, $learner->fresh()->unreadNotifications()->count());
    }
}
