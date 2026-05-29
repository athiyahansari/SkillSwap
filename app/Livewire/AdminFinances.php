<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Booking;
use Carbon\Carbon;
use Livewire\Attributes\On;

class AdminFinances extends Component
{
    use WithPagination;

    public $timeframe = 'yearly';

    public function updatingTimeframe()
    {
        $this->resetPage();
    }

    public function updatedTimeframe()
    {
        $this->dispatch('finance-chart-updated', [
            'chartLabels' => $this->getChartLabels(),
            'chartData' => $this->getChartData(),
            'subjectLabels' => $this->getSubjectLabels(),
            'subjectData' => $this->getSubjectData()
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
    
    public function getSubjectDataProperty()
    {
        return $this->getSubjectData();
    }

    public function getSubjectLabelsProperty()
    {
        return $this->getSubjectLabels();
    }

    private function getChartDataAndLabels()
    {
        $chartLabels = [];
        $chartData = [];

        $query = Booking::where('status', 'completed');

        if ($this->timeframe === 'weekly') {
            $query->where('updated_at', '>=', now()->subDays(6)->startOfDay());
            $earningsData = $query->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->updated_at)->format('D'); // Mon, Tue...
                })
                ->map(function ($row) {
                    return (float) $row->sum('platform_fee');
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
                    return (float) $row->sum('platform_fee');
                });

            for ($i = 29; $i >= 0; $i--) {
                $day = now()->subDays($i)->format('M d');
                $chartLabels[] = $day;
                $chartData[] = $earningsData->get($day, 0);
            }
        } else {
            // yearly
            $query->where('updated_at', '>=', now()->subMonths(11)->startOfMonth());
            $earningsData = $query->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->updated_at)->format('M Y');
                })
                ->map(function ($row) {
                    return (float) $row->sum('platform_fee');
                });

            for ($i = 11; $i >= 0; $i--) {
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

    private function getSubjectDataAndLabels()
    {
        $query = Booking::where('status', 'completed')->with('subject');

        if ($this->timeframe === 'weekly') {
            $query->where('updated_at', '>=', now()->subDays(6)->startOfDay());
        } elseif ($this->timeframe === 'monthly') {
            $query->where('updated_at', '>=', now()->subDays(29)->startOfDay());
        } else {
            $query->where('updated_at', '>=', now()->subMonths(11)->startOfMonth());
        }

        $subjectEarningsData = $query->get()
            ->groupBy(function($booking) {
                return $booking->subject->name;
            })
            ->map(function ($group) {
                return (float) $group->sum('platform_fee');
            })
            ->sortDesc()
            ->take(5);

        return [
            'labels' => $subjectEarningsData->keys()->toArray(),
            'data' => $subjectEarningsData->values()->toArray()
        ];
    }

    private function getSubjectLabels()
    {
        return $this->getSubjectDataAndLabels()['labels'];
    }

    private function getSubjectData()
    {
        return $this->getSubjectDataAndLabels()['data'];
    }

    public function render()
    {
        $baseQuery = Booking::where('status', 'completed');
        
        $totalsQuery = clone $baseQuery;
        if ($this->timeframe === 'weekly') {
            $totalsQuery->where('updated_at', '>=', now()->subDays(6)->startOfDay());
        } elseif ($this->timeframe === 'monthly') {
            $totalsQuery->where('updated_at', '>=', now()->subDays(29)->startOfDay());
        } else {
            $totalsQuery->where('updated_at', '>=', now()->subMonths(11)->startOfMonth());
        }

        $totalEarnings = $totalsQuery->sum('platform_fee');
        $totalTransactions = $totalsQuery->count();

        $transactionsQuery = clone $totalsQuery;
        $recentTransactions = $transactionsQuery->with(['tutorProfile.user', 'learner', 'subject'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('livewire.admin-finances', [
            'totalEarnings' => $totalEarnings,
            'totalTransactions' => $totalTransactions,
            'chartLabels' => $this->getChartLabels(),
            'chartData' => $this->getChartData(),
            'subjectLabels' => $this->getSubjectLabels(),
            'subjectData' => $this->getSubjectData(),
            'recentTransactions' => $recentTransactions
        ]);
    }
}
