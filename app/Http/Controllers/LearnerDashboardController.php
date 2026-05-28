<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class LearnerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $bookings = Booking::where('learner_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->with(['tutorProfile.user', 'subject', 'review'])
            ->get();

        // Sort: pending & confirmed first (date/time asc), completed next (date/time desc)
        $upcoming = $bookings->filter(fn($b) => in_array($b->status, ['pending', 'confirmed']))
            ->sortBy(fn($b) => $b->session_date . ' ' . $b->session_time);

        $completed = $bookings->filter(fn($b) => $b->status === 'completed')
            ->sortByDesc(fn($b) => $b->session_date . ' ' . $b->session_time);

        $upcomingBookings = $upcoming->concat($completed)->take(5);

        // Onboarding data
        $isNewUser = $user->created_at->gt(now()->subDays(7)) && $bookings->isEmpty();
        $emailVerified = !is_null($user->email_verified_at);

        return view('dashboards.learner', compact('upcomingBookings', 'isNewUser', 'emailVerified'));
    }
}
