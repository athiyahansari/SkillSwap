<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Show the form for creating a new review.
     */
    public function create(Booking $booking)
    {
        // Security checks
        if ($booking->learner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status !== 'completed') {
            return redirect()->route('learner.bookings.index')->with('error', 'Reviews can only be left for completed bookings.');
        }

        if ($booking->review()->exists()) {
            return redirect()->route('learner.bookings.index')->with('error', 'You have already submitted a review for this booking.');
        }

        return view('learner.reviews.create', compact('booking'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, Booking $booking)
    {
        // Security checks
        if ($booking->learner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status !== 'completed') {
            return redirect()->route('learner.bookings.index')->with('error', 'Reviews can only be left for completed bookings.');
        }

        if ($booking->review()->exists()) {
            return redirect()->route('learner.bookings.index')->with('error', 'You have already submitted a review for this booking.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'learner_id' => auth()->id(),
            'tutor_profile_id' => $booking->tutor_profile_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('learner.bookings.index')->with('success', 'Your review has been submitted successfully.');
    }
}
