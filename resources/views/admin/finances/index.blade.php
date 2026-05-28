<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Platform Finances') }}
            </h2>
            <span class="px-3 py-1 text-xs font-semibold text-rose-700 bg-rose-100 rounded-full">
                System Administrator
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center">
                    <div class="p-4 rounded-2xl bg-green-50 text-green-600 mr-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Lifetime Revenue</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">${{ number_format($totalEarnings, 2) }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center">
                    <div class="p-4 rounded-2xl bg-sky-50 text-sky-600 mr-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Paid Sessions</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalTransactions) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Revenue Over Time Line Chart -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Revenue Over Time</h3>
                    <div class="relative h-72 w-full">
                        @if(array_sum($chartData) > 0)
                            <canvas id="revenueChart"></canvas>
                        @else
                            <div class="flex items-center justify-center h-full text-slate-400 text-sm">Not enough activity yet to generate insights.</div>
                        @endif
                    </div>
                </div>

                <!-- Revenue by Subject Doughnut Chart -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Top Subjects</h3>
                    <div class="relative h-72 w-full flex justify-center">
                        @if(count($subjectLabels) > 0 && array_sum($subjectData) > 0)
                            <canvas id="subjectChart"></canvas>
                        @else
                            <div class="flex items-center justify-center h-full text-slate-400 text-sm">Not enough activity yet to generate insights.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Transactions Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Recent Marketplace Transactions</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                            <tr>
                                <th class="px-6 py-4">Transaction ID</th>
                                <th class="px-6 py-4">Guide & Subject</th>
                                <th class="px-6 py-4">Learner</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4 text-right">Session Total</th>
                                <th class="px-6 py-4 text-right">Platform Fee</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentTransactions as $transaction)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                        #TRX-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800">{{ $transaction->tutorProfile->user->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $transaction->subject->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $transaction->learner->name }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ \Carbon\Carbon::parse($transaction->updated_at)->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-slate-700">
                                        ${{ number_format($transaction->hourly_rate, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-green-600 bg-green-50/30">
                                        +${{ number_format($transaction->platform_fee, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                        No transactions recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($recentTransactions->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $recentTransactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Chart.js Setup -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Line Chart
            const ctxRevenue = document.getElementById('revenueChart');
            if (ctxRevenue) {
                new Chart(ctxRevenue, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: 'Platform Revenue ($)',
                            data: {!! json_encode($chartData) !!},
                            borderColor: '#10b981', // emerald-500
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
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
                                grid: { color: '#f1f5f9' },
                                border: { display: false },
                                ticks: {
                                    callback: function(value) { return '$' + value; },
                                    color: '#64748b'
                                }
                            },
                            x: {
                                grid: { display: false },
                                border: { display: false },
                                ticks: { color: '#64748b' }
                            }
                        }
                    }
                });
            }

            // Subject Doughnut Chart
            const ctxSubject = document.getElementById('subjectChart');
            if (ctxSubject) {
                new Chart(ctxSubject, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($subjectLabels) !!},
                        datasets: [{
                            data: {!! json_encode($subjectData) !!},
                            backgroundColor: [
                                '#8b5cf6', // violet-500
                                '#ec4899', // pink-500
                                '#3b82f6', // blue-500
                                '#10b981', // emerald-500
                                '#f59e0b'  // amber-500
                            ],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    color: '#475569'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': $' + context.parsed.toFixed(2);
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
