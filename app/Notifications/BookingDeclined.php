<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingDeclined extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'message' => 'Your booking for ' . $this->booking->subject->name . ' on ' . \Carbon\Carbon::parse($this->booking->session_date)->format('M d, Y') . ' was declined.',
            'type' => 'booking_declined',
            'url' => route('learner.bookings.index')
        ];
    }
}
