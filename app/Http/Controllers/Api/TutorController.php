<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TutorResource;
use App\Models\TutorProfile;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    /**
     * Display a listing of verified tutors.
     */
    public function index(Request $request)
    {
        $tutors = TutorProfile::with(['user', 'subjects'])
            ->where('verification_status', 'verified')
            ->paginate(15);

        return TutorResource::collection($tutors);
    }
}
