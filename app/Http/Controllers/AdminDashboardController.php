<?php

namespace App\Http\Controllers;

use App\Models\TutorProfile;
use Illuminate\Http\Request;

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

        return view('dashboards.admin', compact('pendingTutors', 'verifiedTutors', 'rejectedTutors'));
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
}
