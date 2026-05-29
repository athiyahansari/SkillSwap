<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TutorEarningsController extends Controller
{
    public function index()
    {
        $tutorProfile = auth()->user()->tutorProfile;

        if (!$tutorProfile) {
            return redirect()->route('tutor.profile.create')->with('error', 'Please complete your tutor profile first.');
        }

        return view('tutor.earnings.index');
    }
}
