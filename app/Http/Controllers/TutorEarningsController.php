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

        $totalEarnings = Booking::where('tutor_profile_id', $tutorProfile->id)
            ->where('status', 'completed')
            ->sum('tutor_earnings');

        $totalSessions = Booking::where('tutor_profile_id', $tutorProfile->id)
            ->where('status', 'completed')
            ->count();

        // Monthly Earnings Chart Data (Last 6 months)
        $monthlyEarningsData = Booking::where('tutor_profile_id', $tutorProfile->id)
            ->where('status', 'completed')
            ->where('updated_at', '>=', now()->subMonths(5)->startOfMonth())
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->updated_at)->format('M Y');
            })
            ->map(function ($row) {
                return (float) $row->sum('tutor_earnings');
            });

        // Ensure all 6 months exist in the array even if 0
        $chartLabels = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('M Y');
            $chartLabels[] = $month;
            $chartData[] = $monthlyEarningsData->get($month, 0);
        }

        // Recent Earnings Table
        $recentEarnings = Booking::where('tutor_profile_id', $tutorProfile->id)
            ->where('status', 'completed')
            ->with(['learner', 'subject'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('tutor.earnings.index', compact(
            'totalEarnings',
            'totalSessions',
            'chartLabels',
            'chartData',
            'recentEarnings'
        ));
    }
}
