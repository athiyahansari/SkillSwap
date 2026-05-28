<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class StripeWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_webhook_updates_booking_payment_status()
    {
        // Setup config and booking
        Config::set('services.stripe.webhook_secret', 'whsec_test_secret');
        
        $learner = User::factory()->create(['role' => 'learner']);
        $tutor = User::factory()->create(['role' => 'tutor']);
        $tutorProfile = \App\Models\TutorProfile::factory()->create(['user_id' => $tutor->id]);
        $subject = \App\Models\Subject::create(['name' => 'Math', 'slug' => 'math']);
        
        $booking = Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->addDays(2)->format('Y-m-d'),
            'session_time' => '10:00:00',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'stripe_session_id' => 'cs_test_123',
            'hourly_rate' => 20,
            'platform_fee' => 2,
            'tutor_earnings' => 18,
        ]);

        $payload = json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_123',
                    'client_reference_id' => (string) $booking->id,
                ],
            ],
        ]);

        // Generate valid signature
        $timestamp = time();
        $signedPayload = "{$timestamp}.{$payload}";
        $signature = hash_hmac('sha256', $signedPayload, 'whsec_test_secret');
        $sigHeader = "t={$timestamp},v1={$signature}";

        $response = $this->postJson('/stripe/webhook', json_decode($payload, true), [
            'Stripe-Signature' => $sigHeader,
        ]);

        $response->assertStatus(200);
        $this->assertEquals('paid', $booking->fresh()->payment_status);
        $this->assertNotNull($booking->fresh()->paid_at);
    }

    public function test_webhook_rejects_invalid_signature()
    {
        Config::set('services.stripe.webhook_secret', 'whsec_test_secret');

        $payload = json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_123',
                ],
            ],
        ]);

        $response = $this->post('/stripe/webhook', json_decode($payload, true), [
            'Stripe-Signature' => 'invalid_signature',
        ]);

        $response->assertStatus(400);
    }
}
