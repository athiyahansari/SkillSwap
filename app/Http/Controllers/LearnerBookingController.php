<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TutorProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class LearnerBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('learner_id', auth()->id())
            ->with(['tutorProfile.user', 'subject', 'review'])
            ->orderBy('session_date', 'asc')
            ->orderBy('session_time', 'asc')
            ->paginate(10);

        return view('learner.bookings.index', compact('bookings'));
    }

    public function store(Request $request, TutorProfile $tutorProfile)
    {
        $request->validate([
            'subject_id' => [
                'required',
                'exists:tutor_subject,subject_id,tutor_profile_id,' . $tutorProfile->id,
            ],
            'session_date' => 'required|date|after_or_equal:today',
            'session_time' => 'required', // Let's keep it flexible to parse with Carbon
            'notes' => 'nullable|string|max:1000',
        ], [
            'subject_id.exists' => 'The selected subject is not offered by this tutor.',
            'session_date.after_or_equal' => 'The session date must be today or a future date.',
        ]);

        try {
            $time = Carbon::parse($request->session_time)->format('H:i:00');
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'session_time' => 'Please provide a valid time format.',
            ]);
        }
        
        $conflict = Booking::where('tutor_profile_id', $tutorProfile->id)
            ->where('session_date', $request->session_date)
            ->where('session_time', $time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($conflict) {
            throw ValidationException::withMessages([
                'session_time' => 'The tutor is already booked or has a pending request at this date and time.',
            ]);
        }

        Booking::create([
            'learner_id' => auth()->id(),
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $request->subject_id,
            'session_date' => $request->session_date,
            'session_time' => $time,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Booking request submitted successfully.');
    }

    public function cancel(Booking $booking)
    {
        // Check ownership
        if ($booking->learner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only pending bookings can be cancelled by the learner
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending bookings can be cancelled.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Booking request has been cancelled.');
    }
}
