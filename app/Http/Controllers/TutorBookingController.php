<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Notifications\BookingConfirmed;
use App\Notifications\BookingDeclined;
use App\Notifications\ReviewReminder;

class TutorBookingController extends Controller
{
    public function index()
    {
        $tutorProfile = auth()->user()->tutorProfile;

        if (!$tutorProfile) {
            abort(403, 'Tutor profile not found.');
        }

        $bookings = Booking::where('tutor_profile_id', $tutorProfile->id)
            ->with(['learner', 'subject'])
            ->orderBy('session_date', 'asc')
            ->orderBy('session_time', 'asc')
            ->paginate(10);

        return view('tutor.bookings.index', compact('bookings'));
    }

    public function accept(Booking $booking)
    {
        $tutorProfile = auth()->user()->tutorProfile;

        if (!$tutorProfile || $booking->tutor_profile_id !== $tutorProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending bookings can be accepted.');
        }

        $booking->update(['status' => 'confirmed']);

        $booking->learner->notify(new BookingConfirmed($booking));

        return redirect()->back()->with('success', 'Booking request accepted.');
    }

    public function reject(Booking $booking)
    {
        $tutorProfile = auth()->user()->tutorProfile;

        if (!$tutorProfile || $booking->tutor_profile_id !== $tutorProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending bookings can be rejected.');
        }

        $booking->update(['status' => 'cancelled']);

        $booking->learner->notify(new BookingDeclined($booking));

        return redirect()->back()->with('success', 'Booking request rejected.');
    }

    public function complete(Booking $booking)
    {
        $tutorProfile = auth()->user()->tutorProfile;

        if (!$tutorProfile || $booking->tutor_profile_id !== $tutorProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Only confirmed bookings can be completed.');
        }

        $booking->update(['status' => 'completed']);

        $learnerNotified = $booking->learner->notifications()
            ->where('type', ReviewReminder::class)
            ->get()
            ->contains(function ($notification) use ($booking) {
                return isset($notification->data['booking_id']) && $notification->data['booking_id'] == $booking->id;
            });

        if (!$learnerNotified) {
            $booking->learner->notify(new ReviewReminder($booking));
        }

        return redirect()->back()->with('success', 'Booking marked as completed.');
    }
}
