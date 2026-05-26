<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class TutorSubjectController extends Controller
{
    /**
     * Show the form for editing the subjects a tutor teaches.
     */
    public function edit()
    {
        $profile = auth()->user()->tutorProfile;

        if (!$profile) {
            return redirect()->route('tutor.profile.create')
                ->with('info', 'Please create your tutor profile first before selecting subjects.');
        }

        $allSubjects = Subject::all();
        $assignedSubjectIds = $profile->subjects->pluck('id')->toArray();

        return view('tutor.subjects.edit', compact('allSubjects', 'assignedSubjectIds'));
    }

    /**
     * Update the subjects taught by the tutor in storage.
     */
    public function update(Request $request)
    {
        $profile = auth()->user()->tutorProfile;

        if (!$profile) {
            return redirect()->route('tutor.profile.create');
        }

        $validated = $request->validate([
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $profile->subjects()->sync($validated['subjects'] ?? []);

        return redirect()->route('tutor.profile.show')
            ->with('success', 'Your teaching subjects have been updated successfully!');
    }
}
