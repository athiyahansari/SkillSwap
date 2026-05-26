<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class TutorDashboardController extends Controller
{
    public function index()
    {
        $tutorProfile = auth()->user()->tutorProfile;
        $pendingBookings = collect();

        if ($tutorProfile) {
            $pendingBookings = Booking::where('tutor_profile_id', $tutorProfile->id)
                ->where('status', 'pending')
                ->with(['learner', 'subject'])
                ->orderBy('session_date', 'asc')
                ->orderBy('session_time', 'asc')
                ->limit(5)
                ->get();
        }

        return view('dashboards.tutor', compact('pendingBookings'));
    }
}
