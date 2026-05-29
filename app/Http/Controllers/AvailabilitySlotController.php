<?php

namespace App\Http\Controllers;

use App\Models\AvailabilitySlot;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AvailabilitySlotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tutorProfile = auth()->user()->tutorProfile;

        if (!$tutorProfile) {
            return redirect()->route('tutor.profile.create')
                ->with('info', 'Please create your tutor profile first.');
        }

        $daysOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $slots = $tutorProfile->availabilitySlots()
            ->orderBy('start_time')
            ->get()
            ->sortBy(fn ($slot) => array_search($slot->day, $daysOrder));

        return view('tutor.availability.index', compact('slots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tutorProfile = auth()->user()->tutorProfile;

        if (!$tutorProfile) {
            return redirect()->route('tutor.profile.create')
                ->with('error', 'Please create your tutor profile first.');
        }

        $request->validate([
            'day' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        try {
            $start = Carbon::parse($request->start_time);
            $end = Carbon::parse($request->end_time);
        } catch (\Exception $e) {
            return back()->withErrors(['start_time' => 'Invalid time format.'])->withInput();
        }

        if ($start->greaterThanOrEqualTo($end)) {
            return back()->withErrors(['end_time' => 'End time must be after start time.'])->withInput();
        }

        $currentStart = $start->copy();
        $chunks = [];

        while ($currentStart->copy()->addHour()->lte($end)) {
            $chunkStart = $currentStart->format('H:i:00');
            $chunkEnd = $currentStart->copy()->addHour()->format('H:i:00');

            // Check for overlaps:
            // A new slot overlaps with an existing slot if:
            // start_time < existing.end_time AND end_time > existing.start_time
            $overlap = AvailabilitySlot::where('tutor_profile_id', $tutorProfile->id)
                ->where('day', $request->day)
                ->where(function ($query) use ($chunkStart, $chunkEnd) {
                    $query->where('start_time', '<', $chunkEnd)
                          ->where('end_time', '>', $chunkStart);
                })
                ->exists();

            if ($overlap) {
                return back()->withErrors(['start_time' => "The 1-hour slot between {$chunkStart} and {$chunkEnd} overlaps with an existing availability slot."])->withInput();
            }

            $chunks[] = [
                'tutor_profile_id' => $tutorProfile->id,
                'day' => $request->day,
                'start_time' => $chunkStart,
                'end_time' => $chunkEnd,
                'is_available' => true,
            ];

            $currentStart->addHour();
        }

        if (empty($chunks)) {
            return back()->withErrors(['end_time' => 'The availability time range must span at least 1 hour.'])->withInput();
        }

        foreach ($chunks as $chunk) {
            AvailabilitySlot::create($chunk);
        }

        return redirect()->route('tutor.availability.index')
            ->with('success', 'Availability slot added successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AvailabilitySlot $availabilitySlot)
    {
        $tutorProfile = auth()->user()->tutorProfile;

        if (!$tutorProfile || $availabilitySlot->tutor_profile_id !== $tutorProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        $availabilitySlot->delete();

        return redirect()->route('tutor.availability.index')
            ->with('success', 'Availability slot removed successfully!');
    }
}
