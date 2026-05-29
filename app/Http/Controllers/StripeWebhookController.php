<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Notifications\PaymentSuccessful;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        $event = null;

        try {
            $event = Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch(UnexpectedValueException $e) {
            // Invalid payload
            Log::error('Stripe webhook error: Invalid payload', ['error' => $e->getMessage()]);
            return response('Invalid payload', 400);
        } catch(SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Stripe webhook error: Invalid signature', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                
                // Retrieve the booking via client_reference_id
                $bookingId = $session->client_reference_id;
                
                if ($bookingId) {
                    $booking = Booking::find($bookingId);
                    if ($booking) {
                        $booking->update([
                            'payment_status' => 'paid',
                            'paid_at' => now(),
                        ]);
                        $booking->learner->notify(new PaymentSuccessful($booking));
                        $booking->tutorProfile->user->notify(new PaymentSuccessful($booking));
                        Log::info("Booking {$booking->id} marked as paid.");
                    } else {
                        Log::warning("Stripe webhook: Booking not found for client_reference_id: {$bookingId}");
                    }
                } else {
                    Log::warning("Stripe webhook: No client_reference_id in session.");
                }
                break;
            default:
                Log::info('Stripe webhook: Received unknown event type ' . $event->type);
        }

        return response('Webhook handled', 200);
    }
}
