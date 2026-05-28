<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminFinanceController extends Controller
{
    public function index()
    {
        $totalEarnings = Booking::where('status', 'completed')->sum('platform_fee');
        $totalTransactions = Booking::where('status', 'completed')->count();

        // Monthly Earnings Chart Data (Last 12 months)
        $monthlyEarningsData = Booking::where('status', 'completed')
            ->where('updated_at', '>=', now()->subMonths(11)->startOfMonth())
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->updated_at)->format('M Y');
            })
            ->map(function ($row) {
                return (float) $row->sum('platform_fee');
            });

        // Ensure all 12 months exist in the array even if 0
        $chartLabels = [];
        $chartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('M Y');
            $chartLabels[] = $month;
            $chartData[] = $monthlyEarningsData->get($month, 0);
        }

        // Subject Earnings Distribution
        $subjectEarningsData = Booking::where('status', 'completed')
            ->with('subject')
            ->get()
            ->groupBy(function($booking) {
                return $booking->subject->name;
            })
            ->map(function ($group) {
                return (float) $group->sum('platform_fee');
            })
            ->sortDesc()
            ->take(5);

        $subjectLabels = $subjectEarningsData->keys()->toArray();
        $subjectData = $subjectEarningsData->values()->toArray();

        // Recent Transactions Table
        $recentTransactions = Booking::where('status', 'completed')
            ->with(['tutorProfile.user', 'learner', 'subject'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('admin.finances.index', compact(
            'totalEarnings',
            'totalTransactions',
            'chartLabels',
            'chartData',
            'subjectLabels',
            'subjectData',
            'recentTransactions'
        ));
    }
}
