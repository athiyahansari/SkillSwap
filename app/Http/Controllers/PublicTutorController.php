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
        // Fetch all subjects for the filter dropdown
        $allSubjects = Subject::orderBy('name')->get();

        // Eager load relationships to avoid N+1 query issues
        $query = TutorProfile::with(['user', 'subjects', 'reviews'])
            ->whereNotNull('bio')
            ->whereNotNull('hourly_rate')
            ->whereNotNull('profile_photo');

        // Filter by search query (matches name, bio, or subject name)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                })
                ->orWhere('bio', 'like', "%{$search}%")
                ->orWhereHas('subjects', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Filter by subject id
        if ($request->filled('subject')) {
            $subjectId = $request->input('subject');
            $query->whereHas('subjects', function ($q) use ($subjectId) {
                $q->where('subjects.id', $subjectId);
            });
        }

        // Paginate by 9 results per page
        $tutors = $query->latest()->paginate(9)->withQueryString();

        return view('tutors.index', compact('tutors', 'allSubjects'));
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
