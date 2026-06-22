<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TutorMessageAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // MongoDB collections are not rolled back by RefreshDatabase — clear manually
        Conversation::query()->delete();
        Message::query()->delete();
    }

    /**
     * Guest cannot access the conversation initiation route.
     */
    public function test_guest_cannot_initiate_conversation(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $response = $this->get(route('tutor.conversations.initiate', $learner));
        $response->assertRedirect('/login');
    }

    /**
     * Learner cannot access the conversation initiation route restricted to tutors.
     */
    public function test_learner_cannot_access_tutor_initiation_route(): void
    {
        $learner1 = User::factory()->create(['role' => 'learner']);
        $learner2 = User::factory()->create(['role' => 'learner']);
        
        $this->actingAs($learner1);

        $response = $this->get(route('tutor.conversations.initiate', $learner2));
        // Redirects to learner dashboard because of role middleware redirection
        $response->assertRedirect(route('learner.dashboard'));
    }

    /**
     * Tutor cannot initiate a conversation if no booking and no prior conversation exists.
     */
    public function test_tutor_cannot_initiate_conversation_without_booking_or_existing_thread(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $learner = User::factory()->create(['role' => 'learner']);

        $this->actingAs($tutor);

        // Access route, should redirect back with error
        $response = $this->get(route('tutor.conversations.initiate', $learner));
        $response->assertRedirect();
        $response->assertSessionHas('error', 'You can only message a learner if you have a booking with them or if they initiated a conversation first.');

        $this->assertEquals(0, Conversation::count());
    }

    /**
     * Tutor CAN initiate a conversation if a booking exists.
     */
    public function test_tutor_can_initiate_conversation_with_existing_booking(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        $profile = TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $learner = User::factory()->create(['role' => 'learner']);
        $subject = Subject::create(['name' => 'Math', 'slug' => 'math']);

        // Create booking
        Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $profile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->addDays(2)->format('Y-m-d'),
            'session_time' => '10:00:00',
            'status' => 'pending',
        ]);

        $this->actingAs($tutor);

        $response = $this->get(route('tutor.conversations.initiate', $learner));
        
        $this->assertEquals(1, Conversation::count());
        $conversation = Conversation::first();
        
        $response->assertRedirect(route('inbox.show', $conversation));
        $this->assertEquals($learner->id, $conversation->learner_id);
        $this->assertEquals($tutor->id, $conversation->tutor_id);
    }

    /**
     * Tutor CAN initiate/retrieve conversation if one already exists.
     */
    public function test_tutor_can_initiate_conversation_if_already_exists(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $learner = User::factory()->create(['role' => 'learner']);

        // Create conversation beforehand
        $conversation = Conversation::create([
            'learner_id' => $learner->id,
            'tutor_id' => $tutor->id,
        ]);

        $this->actingAs($tutor);

        $response = $this->get(route('tutor.conversations.initiate', $learner));
        
        $response->assertRedirect(route('inbox.show', $conversation));
        $this->assertEquals(1, Conversation::count());
    }
}
