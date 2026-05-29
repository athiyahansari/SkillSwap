<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Platform Insights') }}
            </h2>
            <span class="px-3 py-1 text-xs font-semibold text-rose-700 bg-rose-100 rounded-full">
                System Administrator
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Header -->
            <div class="bg-gradient-to-r from-rose-600 via-pink-700 to-red-800 rounded-3xl p-8 md:p-12 shadow-xl relative overflow-hidden text-white">
                <div class="absolute right-0 bottom-0 top-0 w-1/3 opacity-10 pointer-events-none">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0,100 C30,40 70,60 100,0 L100,100 Z" fill="currentColor"></path>
                    </svg>
                </div>
                <div class="relative z-10 space-y-4">
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                        Welcome back, {{ auth()->user()->name }}! 👑
                    </h1>
                    <p class="text-rose-100 text-lg max-w-xl">
                        SkillSwap system management portal. Verify guide expertise, monitor marketplace earnings, and audit platform statistics.
                    </p>
                </div>
            </div>

            <!-- Stats Grid Row 1 (Users & Approvals) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Users -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full">Community</span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">{{ number_format($totalUsers) }}</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Total Users</p>
                    </div>
                </div>

                <!-- Total Learners -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">{{ number_format($totalLearners) }}</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Total Learners</p>
                    </div>
                </div>

                <!-- Total Guides -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-purple-50 text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">{{ number_format($totalGuides) }}</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Total Guides</p>
                    </div>
                </div>

                <!-- Pending Approvals -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-amber-50 text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        @if($pendingExpertiseVerifications > 0)
                            <span class="text-xs font-semibold text-rose-600 bg-rose-50 px-2.5 py-0.5 rounded-full">Action Needed</span>
                        @endif
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">{{ $pendingExpertiseVerifications }}</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Pending Verifications</p>
                    </div>
                </div>
            </div>

            <!-- Stats Grid Row 2 (Marketplace) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Completed Sessions -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">{{ number_format($completedSessions) }}</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Completed Sessions</p>
                    </div>
                </div>

                <!-- Active Bookings -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-sky-50 text-sky-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">{{ number_format($pendingBookings) }}</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Pending Bookings</p>
                    </div>
                </div>

                <!-- Platform Revenue -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-green-50 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">${{ number_format($totalPlatformEarnings, 2) }}</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Marketplace Revenue</p>
                    </div>
                </div>

                <!-- Average Rating -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-yellow-50 text-yellow-500">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">{{ number_format($averagePlatformRating, 1) }}</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Avg Platform Rating</p>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Content Area -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Main Activity Column -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Recent Completed Sessions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                            <h2 class="text-lg font-bold text-slate-800">Recent Completed Sessions</h2>
                            <span class="text-xs font-semibold bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full">Live Feed</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-4">Guide & Subject</th>
                                        <th class="px-6 py-4">Learner</th>
                                        <th class="px-6 py-4">Session Date</th>
                                        <th class="px-6 py-4 text-right">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($recentCompletedSessions as $session)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-slate-800">{{ $session->tutorProfile->user->name }}</div>
                                                <div class="text-xs text-slate-500">{{ $session->subject->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-slate-600">
                                                {{ $session->learner->name }}
                                            </td>
                                            <td class="px-6 py-4 text-slate-500">
                                                {{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-medium text-green-600">
                                                +${{ number_format($session->platform_fee, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                                No completed sessions yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pending Approvals list -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-slate-800">Pending Guide Applications</h2>
                        </div>
                        
                        <div class="divide-y divide-slate-100">
                            @forelse ($pendingTutors as $tutor)
                                <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 first:pt-0 last:pb-0">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex-shrink-0 flex items-center justify-center font-bold text-lg border border-amber-100/50">
                                            {{ strtoupper(substr($tutor->user->name ?? 'T', 0, 2)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800">{{ $tutor->user->name }}</h4>
                                            <p class="text-sm text-slate-500">Subjects: {{ $tutor->subjects->pluck('name')->implode(', ') ?: 'None' }}</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Rate: ${{ number_format($tutor->hourly_rate, 2) }}/hr | Edu: {{ $tutor->education }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between sm:justify-end gap-4">
                                        <div class="text-left sm:text-right">
                                            <p class="text-xs font-semibold text-slate-500">Applied {{ $tutor->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <form action="{{ route('admin.tutors.verify', $tutor) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to VERIFY this guide?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm hover:shadow transition">
                                                    Verify
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.tutors.reject', $tutor) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to REJECT this guide?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-lg shadow-sm hover:shadow transition">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-slate-500">No pending guide applications.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Secondary Sidebar -->
                <div class="space-y-8">
                    
                    <!-- Top Active Guides -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                            <h2 class="text-lg font-bold text-slate-800">Top Guides</h2>
                            <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @forelse($topActiveGuides as $guide)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs">
                                                {{ strtoupper(substr($guide->user->name ?? 'G', 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-sm text-slate-800">{{ $guide->user->name }}</div>
                                            </div>
                                        </div>
                                        <div class="text-xs font-medium text-slate-500">
                                            {{ $guide->bookings_count }} sessions
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-sm text-slate-500">No active guides.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Admin settings / actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-6">
                        <h2 class="text-lg font-bold text-slate-800">Administrative Tools</h2>
                        <div class="grid grid-cols-1 gap-4">
                            <a href="{{ route('admin.users.index') }}" class="flex items-center p-3 rounded-xl hover:bg-slate-50 border border-slate-100 transition-colors group">
                                <div class="p-2.5 rounded-lg bg-rose-50 text-rose-600 mr-4 group-hover:bg-rose-600 group-hover:text-white transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">User Management</h4>
                                    <p class="text-xs text-slate-500">View and edit user settings</p>
                                </div>
                            </a>

                            <a href="#" class="flex items-center p-3 rounded-xl hover:bg-slate-50 border border-slate-100 transition-colors group">
                                <div class="p-2.5 rounded-lg bg-slate-100 text-slate-600 mr-4 group-hover:bg-slate-700 group-hover:text-white transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">System Settings</h4>
                                    <p class="text-xs text-slate-500">Feature flags & API keys</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>