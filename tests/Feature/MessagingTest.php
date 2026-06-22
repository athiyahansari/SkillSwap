<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TutorProfile;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // MongoDB collections are not rolled back by RefreshDatabase — clear manually
        Conversation::query()->delete();
        Message::query()->delete();
    }

    public function test_learner_can_send_message_to_tutor()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $tutorUser = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutorUser->id]);

        $this->withoutExceptionHandling();
        $response = $this->actingAs($learner)->post(route('tutors.message.store', $tutorProfile), [
            'body' => 'Hello, I want to learn!',
        ]);

        // Conversation is stored in MongoDB — use model query instead of assertDatabaseHas
        $conversation = Conversation::where('learner_id', $learner->id)
            ->where('tutor_id', $tutorUser->id)
            ->first();

        $this->assertNotNull($conversation, 'Expected a conversation to be created in MongoDB.');

        // Message is stored in MongoDB — query it directly
        $message = Message::where('conversation_id', (string) $conversation->id)
            ->where('sender_id', $learner->id)
            ->where('body', 'Hello, I want to learn!')
            ->first();

        $this->assertNotNull($message, 'Expected a message to be created in MongoDB.');

        $response->assertRedirect(route('inbox.show', $conversation));
    }

    public function test_duplicate_conversations_are_prevented()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $tutorUser = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutorUser->id]);

        // First message
        $this->actingAs($learner)->post(route('tutors.message.store', $tutorProfile), [
            'body' => 'First message',
        ]);

        // Second message
        $this->actingAs($learner)->post(route('tutors.message.store', $tutorProfile), [
            'body' => 'Second message',
        ]);

        $this->assertEquals(1, Conversation::count());
        $this->assertEquals(2, $learner->conversationsAsLearner->first()->messages()->count());
    }

    public function test_participants_can_view_inbox_and_conversation()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $tutorUser = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutorUser->id]);

        $conversation = Conversation::create([
            'learner_id' => $learner->id,
            'tutor_id' => $tutorUser->id,
        ]);

        $conversation->messages()->create([
            'sender_id' => $learner->id,
            'body' => 'Hi Tutor',
        ]);

        // Learner can view inbox
        $this->actingAs($learner)->get(route('inbox.index'))->assertStatus(200);
        $this->actingAs($learner)->get(route('inbox.show', $conversation))->assertStatus(200);

        // Tutor can view inbox
        $this->actingAs($tutorUser)->get(route('inbox.index'))->assertStatus(200);
        $this->actingAs($tutorUser)->get(route('inbox.show', $conversation))->assertStatus(200);
    }

    public function test_unauthorized_users_cannot_view_conversations()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $tutorUser = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = TutorProfile::factory()->create(['user_id' => $tutorUser->id]);

        $conversation = Conversation::create([
            'learner_id' => $learner->id,
            'tutor_id' => $tutorUser->id,
        ]);

        $otherUser = User::factory()->create(['role' => 'learner']);

        $this->actingAs($otherUser)->get(route('inbox.show', $conversation))->assertStatus(403);
        $this->actingAs($otherUser)->post(route('inbox.messages.store', $conversation), ['body' => 'Hack message'])->assertStatus(403);
    }
}
