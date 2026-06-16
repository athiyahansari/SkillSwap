<?php

namespace App\Http\Controllers;

use App\Models\TutorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TutorProfileController extends Controller
{
    /**
     * Display the authenticated tutor's profile.
     */
    public function show()
    {
        $profile = auth()->user()->tutorProfile;

        if (!$profile) {
            return redirect()->route('tutor.profile.create')
                ->with('info', 'Please create your tutor profile first.');
        }

        $profile->load(['subjects', 'availabilitySlots']);

        return view('tutor.profile.show', compact('profile'));
    }

    /**
     * Show the form for creating a new tutor profile.
     */
    public function create()
    {
        if (auth()->user()->tutorProfile) {
            return redirect()->route('tutor.profile.show')
                ->with('info', 'You already have a tutor profile.');
        }

        return view('tutor.profile.create');
    }

    /**
     * Store a newly created tutor profile in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->tutorProfile) {
            return redirect()->route('tutor.profile.show')
                ->with('error', 'You already have a tutor profile.');
        }

        $validated = $request->validate([
            'bio' => 'required|string|min:20',
            'hourly_rate' => 'required|numeric|min:0|max:999.99',
            'education' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('tutor_photos', 'public');
            $validated['profile_photo'] = $path;
        }

        $validated['user_id'] = auth()->id();
        $validated['verification_status'] = 'pending';

        TutorProfile::create($validated);

        return redirect()->route('tutor.profile.show')
            ->with('success', 'Tutor profile created successfully!');
    }

    /**
     * Show the form for editing the tutor profile.
     */
    public function edit()
    {
        $profile = auth()->user()->tutorProfile;

        if (!$profile) {
            return redirect()->route('tutor.profile.create')
                ->with('info', 'Please create your tutor profile first.');
        }

        return view('tutor.profile.edit', compact('profile'));
    }

    /**
     * Update the tutor profile in storage.
     */
    public function update(Request $request)
    {
        $profile = auth()->user()->tutorProfile;

        if (!$profile) {
            return redirect()->route('tutor.profile.create')
                ->with('error', 'Tutor profile not found.');
        }

        $validated = $request->validate([
            'bio' => 'required|string|min:20',
            'hourly_rate' => 'required|numeric|min:0|max:999.99',
            'education' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if it exists
            if ($profile->profile_photo) {
                Storage::disk('public')->delete($profile->profile_photo);
            }

            $path = $request->file('profile_photo')->store('tutor_photos', 'public');
            $validated['profile_photo'] = $path;
        }

        $profile->update($validated);

        return redirect()->route('tutor.profile.show')
            ->with('success', 'Tutor profile updated successfully!');
    }
}
