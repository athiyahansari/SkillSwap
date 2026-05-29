<?php

namespace App\Http\Controllers;

use App\Models\TutorProfile;
use App\Models\User;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $pendingTutors = TutorProfile::where('verification_status', 'pending')
            ->with(['user', 'subjects'])
            ->get();

        $verifiedTutors = TutorProfile::where('verification_status', 'verified')
            ->with(['user', 'subjects'])
            ->get();

        $rejectedTutors = TutorProfile::where('verification_status', 'rejected')
            ->with(['user', 'subjects'])
            ->get();

        // Platform Insights
        $totalUsers = User::count();
        $totalLearners = User::where('role', 'learner')->count();
        $totalGuides = User::where('role', 'tutor')->count();
        
        $completedSessions = Booking::where('status', 'completed')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $totalPlatformEarnings = Booking::where('status', 'completed')->sum('platform_fee');
        
        $averagePlatformRating = Review::avg('rating') ?? 0;
        $pendingExpertiseVerifications = $pendingTutors->count();

        // Marketplace Activity Tables
        $recentCompletedSessions = Booking::where('status', 'completed')
            ->with(['learner', 'tutorProfile.user', 'subject'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        $topActiveGuides = TutorProfile::with('user')
            ->withCount(['bookings' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();

        return view('dashboards.admin', compact(
            'pendingTutors', 
            'verifiedTutors', 
            'rejectedTutors',
            'totalUsers',
            'totalLearners',
            'totalGuides',
            'completedSessions',
            'pendingBookings',
            'totalPlatformEarnings',
            'averagePlatformRating',
            'pendingExpertiseVerifications',
            'recentCompletedSessions',
            'topActiveGuides'
        ));
    }

    public function verify(TutorProfile $tutorProfile)
    {
        $tutorProfile->update(['verification_status' => 'verified']);

        return redirect()->back()->with('success', "Tutor {$tutorProfile->user->name} has been verified successfully.");
    }

    public function reject(TutorProfile $tutorProfile)
    {
        $tutorProfile->update(['verification_status' => 'rejected']);

        return redirect()->back()->with('success', "Tutor {$tutorProfile->user->name} verification has been rejected.");
    }

    public function revert(TutorProfile $tutorProfile)
    {
        $tutorProfile->update(['verification_status' => 'pending']);

        return redirect()->back()->with('success', "Tutor {$tutorProfile->user->name} verification status has been reverted back to pending.");
    }

    public function users()
    {
        return view('admin.users.index');
    }
}
