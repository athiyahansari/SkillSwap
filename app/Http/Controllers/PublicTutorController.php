<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\ProfileView;
use Illuminate\Http\Request;

class PublicTutorController extends Controller
{
    /**
     * Display a listing of verified tutor profiles.
     */
    public function index(Request $request)
    {
        return view('tutors.index');
    }

    /**
     * Display the specified public tutor profile details.
     */
    public function show(TutorProfile $tutorProfile)
    {
        // Eager load relationships for showing detail page
        $tutorProfile->load(['user', 'subjects', 'reviews.learner', 'availabilitySlots']);

        // Log Profile View to MongoDB Analytics
        ProfileView::create([
            'tutor_profile_id' => $tutorProfile->id,
            'ip_address'       => request()->ip(),
            'user_agent'       => request()->userAgent(),
            'viewed_at'        => now(),
        ]);

        // Calculate aggregate statistics
        $averageRating = $tutorProfile->reviews->avg('rating');
        $reviewsCount = $tutorProfile->reviews->count();

        return view('tutors.show', compact('tutorProfile', 'averageRating', 'reviewsCount'));
    }
}
