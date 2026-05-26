<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class LearnerDashboardController extends Controller
{
    public function index()
    {
        $upcomingBookings = Booking::where('learner_id', auth()->id())
            ->whereIn('status', ['pending', 'confirmed'])
            ->with(['tutorProfile.user', 'subject'])
            ->orderBy('session_date', 'asc')
            ->orderBy('session_time', 'asc')
            ->limit(5)
            ->get();

        return view('dashboards.learner', compact('upcomingBookings'));
    }
}
