<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\TutorProfile;
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

        // Calculate aggregate statistics
        $averageRating = $tutorProfile->reviews->avg('rating');
        $reviewsCount = $tutorProfile->reviews->count();

        return view('tutors.show', compact('tutorProfile', 'averageRating', 'reviewsCount'));
    }
}
