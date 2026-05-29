<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Booking;
use App\Notifications\UpcomingSessionReminder;
use App\Notifications\ReviewReminder;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    // Upcoming session reminders (24 hours before)
    $upcomingBookings = Booking::where('status', 'confirmed')
        ->where('session_date', now()->addDay()->format('Y-m-d'))
        ->get();

    foreach ($upcomingBookings as $booking) {
        // Prevent duplicate upcoming session reminders for learner
        $learnerNotified = $booking->learner->notifications()
            ->where('type', UpcomingSessionReminder::class)
            ->get()
            ->contains(function ($notification) use ($booking) {
                return isset($notification->data['booking_id']) && $notification->data['booking_id'] == $booking->id;
            });

        if (!$learnerNotified) {
            $booking->learner->notify(new UpcomingSessionReminder($booking));
        }

        // Prevent duplicate upcoming session reminders for tutor
        $tutorNotified = $booking->tutorProfile->user->notifications()
            ->where('type', UpcomingSessionReminder::class)
            ->get()
            ->contains(function ($notification) use ($booking) {
                return isset($notification->data['booking_id']) && $notification->data['booking_id'] == $booking->id;
            });

        if (!$tutorNotified) {
            $booking->tutorProfile->user->notify(new UpcomingSessionReminder($booking));
        }
    }

    // Scheduled Review Reminders (safeguard for completed sessions)
    $completedBookings = Booking::where('status', 'completed')
        ->doesntHave('review')
        ->get();
        
    foreach ($completedBookings as $booking) {
        $learnerNotified = $booking->learner->notifications()
            ->where('type', ReviewReminder::class)
            ->get()
            ->contains(function ($notification) use ($booking) {
                return isset($notification->data['booking_id']) && $notification->data['booking_id'] == $booking->id;
            });

        if (!$learnerNotified) {
            $booking->learner->notify(new ReviewReminder($booking));
        }
    }
})->hourly();
