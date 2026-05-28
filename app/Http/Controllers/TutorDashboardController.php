<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\AvailabilitySlot;
use Illuminate\Http\Request;

class TutorDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $tutorProfile = $user->tutorProfile;
        $pendingBookings = collect();

        // Onboarding completion data
        $onboarding = [
            'has_profile' => false,
            'has_bio' => false,
            'has_photo' => false,
            'has_subjects' => false,
            'has_availability' => false,
            'verification_status' => 'pending',
        ];

        if ($tutorProfile) {
            $tutorProfile->loadCount('subjects');

            $pendingBookings = Booking::where('tutor_profile_id', $tutorProfile->id)
                ->where('status', 'pending')
                ->with(['learner', 'subject'])
                ->orderBy('session_date', 'asc')
                ->orderBy('session_time', 'asc')
                ->limit(5)
                ->get();

            $hasAvailability = AvailabilitySlot::where('tutor_profile_id', $tutorProfile->id)->exists();

            $onboarding = [
                'has_profile' => true,
                'has_bio' => !empty($tutorProfile->bio),
                'has_photo' => !empty($tutorProfile->profile_photo),
                'has_subjects' => $tutorProfile->subjects_count > 0,
                'has_availability' => $hasAvailability,
                'verification_status' => $tutorProfile->verification_status,
            ];
        }

        $profileComplete = $onboarding['has_profile']
            && $onboarding['has_bio']
            && $onboarding['has_photo']
            && $onboarding['has_subjects']
            && $onboarding['has_availability'];

        $completionPercent = 0;
        if ($onboarding['has_profile']) {
            $steps = ['has_bio', 'has_photo', 'has_subjects', 'has_availability'];
            $done = collect($steps)->filter(fn($s) => $onboarding[$s])->count();
            $completionPercent = (int) round(($done / count($steps)) * 100);
        }

        $emailVerified = !is_null($user->email_verified_at);

        return view('dashboards.tutor', compact(
            'pendingBookings',
            'onboarding',
            'profileComplete',
            'completionPercent',
            'emailVerified'
        ));
    }
}
