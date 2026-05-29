<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Booking;
use Carbon\Carbon;
use Livewire\Attributes\On;

class TutorEarnings extends Component
{
    use WithPagination;

    public $timeframe = '6months';

    public function updatingTimeframe()
    {
        $this->resetPage();
    }

    public function updatedTimeframe()
    {
        $this->dispatch('tutor-chart-updated', [
            'chartLabels' => $this->getChartLabels(),
            'chartData' => $this->getChartData(),
        ]);
    }

    public function getChartDataProperty()
    {
        return $this->getChartData();
    }

    public function getChartLabelsProperty()
    {
        return $this->getChartLabels();
    }

    private function getChartDataAndLabels()
    {
        $tutorProfile = auth()->user()->tutorProfile;
        if (!$tutorProfile) return ['labels' => [], 'data' => []];

        $chartLabels = [];
        $chartData = [];

        $query = Booking::where('tutor_profile_id', $tutorProfile->id)
            ->where('status', 'completed');

        if ($this->timeframe === 'weekly') {
            $query->where('updated_at', '>=', now()->subDays(6)->startOfDay());
            $earningsData = $query->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->updated_at)->format('D'); // Mon, Tue...
                })
                ->map(function ($row) {
                    return (float) $row->sum('tutor_earnings');
                });

            for ($i = 6; $i >= 0; $i--) {
                $day = now()->subDays($i)->format('D');
                $chartLabels[] = $day;
                $chartData[] = $earningsData->get($day, 0);
            }
        } elseif ($this->timeframe === 'monthly') {
            $query->where('updated_at', '>=', now()->subDays(29)->startOfDay());
            $earningsData = $query->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->updated_at)->format('M d'); // Jan 01
                })
                ->map(function ($row) {
                    return (float) $row->sum('tutor_earnings');
                });

            for ($i = 29; $i >= 0; $i--) {
                $day = now()->subDays($i)->format('M d');
                $chartLabels[] = $day;
                $chartData[] = $earningsData->get($day, 0);
            }
        } else {
            // 6months
            $query->where('updated_at', '>=', now()->subMonths(5)->startOfMonth());
            $earningsData = $query->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->updated_at)->format('M Y');
                })
                ->map(function ($row) {
                    return (float) $row->sum('tutor_earnings');
                });

            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i)->format('M Y');
                $chartLabels[] = $month;
                $chartData[] = $earningsData->get($month, 0);
            }
        }

        return ['labels' => $chartLabels, 'data' => $chartData];
    }

    private function getChartLabels()
    {
        return $this->getChartDataAndLabels()['labels'];
    }

    private function getChartData()
    {
        return $this->getChartDataAndLabels()['data'];
    }

    public function render()
    {
        $tutorProfile = auth()->user()->tutorProfile;

        if (!$tutorProfile) {
            return view('livewire.tutor-earnings', [
                'totalEarnings' => 0,
                'totalSessions' => 0,
                'chartLabels' => [],
                'chartData' => [],
                'recentEarnings' => collect([])
            ]);
        }

        $baseQuery = Booking::where('tutor_profile_id', $tutorProfile->id)
            ->where('status', 'completed');
        
        $totalsQuery = clone $baseQuery;
        if ($this->timeframe === 'weekly') {
            $totalsQuery->where('updated_at', '>=', now()->subDays(6)->startOfDay());
        } elseif ($this->timeframe === 'monthly') {
            $totalsQuery->where('updated_at', '>=', now()->subDays(29)->startOfDay());
        } else {
            $totalsQuery->where('updated_at', '>=', now()->subMonths(5)->startOfMonth());
        }

        $totalEarnings = $totalsQuery->sum('tutor_earnings');
        $totalSessions = $totalsQuery->count();

        $transactionsQuery = clone $totalsQuery;
        $recentEarnings = $transactionsQuery->with(['learner', 'subject'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('livewire.tutor-earnings', [
            'totalEarnings' => $totalEarnings,
            'totalSessions' => $totalSessions,
            'chartLabels' => $this->getChartLabels(),
            'chartData' => $this->getChartData(),
            'recentEarnings' => $recentEarnings
        ]);
    }
}
