<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class UpcomingSessionReminder extends Notification implements ShouldQueue
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
            'message' => 'Reminder: You have an upcoming session for ' . $this->booking->subject->name . ' tomorrow at ' . \Carbon\Carbon::parse($this->booking->session_time)->format('h:i A') . '.',
            'type' => 'upcoming_session',
            'url' => '#' // Could point to bookings index
        ];
    }
}
