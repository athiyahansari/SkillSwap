<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Request;

class LearnerBookingController extends Controller
{
    /**
     * Display a listing of the learner's bookings.
     */
    public function index(Request $request)
    {
        // Authorization is handled via middleware route grouping
        $bookings = Booking::with(['subject', 'tutorProfile.user'])
            ->where('learner_id', $request->user()->id)
            ->latest('session_date')
            ->paginate(10);

        return BookingResource::collection($bookings);
    }
}
