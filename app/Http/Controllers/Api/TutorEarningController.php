<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class TutorEarningController extends Controller
{
    /**
     * Display the tutor's earnings metrics and recent paid bookings.
     */
    public function index(Request $request)
    {
        $tutorProfile = $request->user()->tutorProfile;

        if (!$tutorProfile) {
            return response()->json(['message' => 'Tutor profile not found.'], 404);
        }

        $paidBookings = Booking::where('tutor_profile_id', $tutorProfile->id)
            ->where('payment_status', 'paid')
            ->get();

        $totalEarnings = $paidBookings->sum('tutor_earnings');
        $totalBookings = $paidBookings->count();
        $thisMonthEarnings = $paidBookings->where('session_date', '>=', now()->startOfMonth())->sum('tutor_earnings');

        return response()->json([
            'data' => [
                'total_earnings' => $totalEarnings,
                'this_month_earnings' => $thisMonthEarnings,
                'total_paid_bookings' => $totalBookings,
                'recent_transactions' => $paidBookings->sortByDesc('session_date')->take(5)->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'amount' => $booking->tutor_earnings,
                        'date' => $booking->session_date,
                    ];
                })->values()
            ]
        ]);
    }
}
