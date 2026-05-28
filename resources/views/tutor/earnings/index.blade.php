<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('My Earnings') }}
            </h2>
            <span class="px-3 py-1 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full">
                Guide Portal
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-8 shadow-md text-white flex items-center relative overflow-hidden">
                    <div class="absolute right-0 bottom-0 top-0 w-1/2 opacity-10 pointer-events-none">
                        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <path d="M0,100 C30,40 70,60 100,0 L100,100 Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <div class="p-4 rounded-2xl bg-white/20 backdrop-blur-sm mr-6 z-10">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="z-10">
                        <p class="text-emerald-100 font-medium uppercase tracking-wider text-sm">Total Lifetime Earnings</p>
                        <h3 class="text-4xl font-extrabold mt-1">${{ number_format($totalEarnings, 2) }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 flex items-center">
                    <div class="p-4 rounded-2xl bg-purple-50 text-purple-600 mr-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Completed Sessions</p>
                        <h3 class="text-4xl font-extrabold text-slate-800 mt-1">{{ number_format($totalSessions) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Earnings Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Your Earnings Trend (Last 6 Months)</h3>
                </div>
                <div class="relative h-80 w-full flex justify-center">
                    @if(array_sum($chartData) > 0)
                        <canvas id="tutorEarningsChart"></canvas>
                    @else
                        <div class="flex items-center justify-center h-full text-slate-400 text-sm">Not enough activity yet to generate insights.</div>
                    @endif
                </div>
            </div>

            <!-- Recent Earnings Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Earnings History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                            <tr>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Learner</th>
                                <th class="px-6 py-4">Subject</th>
                                <th class="px-6 py-4 text-right">Session Rate</th>
                                <th class="px-6 py-4 text-right">Platform Fee (10%)</th>
                                <th class="px-6 py-4 text-right">Your Take Home</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentEarnings as $earning)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-slate-600 font-medium">
                                        {{ \Carbon\Carbon::parse($earning->updated_at)->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-800">
                                        {{ $earning->learner->name }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ $earning->subject->name }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-slate-500">
                                        ${{ number_format($earning->hourly_rate, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-rose-500">
                                        -${{ number_format($earning->platform_fee, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-emerald-600 bg-emerald-50/50">
                                        +${{ number_format($earning->tutor_earnings, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                        You haven't completed any paid sessions yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($recentEarnings->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $recentEarnings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Chart.js Setup -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('tutorEarningsChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: 'Your Earnings ($)',
                            data: {!! json_encode($chartData) !!},
                            backgroundColor: '#10b981', // emerald-500
                            borderRadius: 6,
                            barPercentage: 0.6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return '$' + context.parsed.y.toFixed(2);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f1f5f9', borderDash: [5, 5] },
                                border: { display: false },
                                ticks: {
                                    callback: function(value) { return '$' + value; },
                                    color: '#64748b',
                                    font: { family: "'Inter', sans-serif" }
                                }
                            },
                            x: {
                                grid: { display: false },
                                border: { display: false },
                                ticks: { 
                                    color: '#64748b',
                                    font: { family: "'Inter', sans-serif", weight: '500' }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
