<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Tutor Dashboard') }}
            </h2>
            <span class="px-3 py-1 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full">
                Tutor Portal
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Header -->
            <div class="bg-gradient-to-r from-emerald-600 via-teal-700 to-cyan-800 rounded-3xl p-8 md:p-12 shadow-xl relative overflow-hidden text-white">
                <div class="absolute right-0 bottom-0 top-0 w-1/3 opacity-10 pointer-events-none">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0,100 C30,40 70,60 100,0 L100,100 Z" fill="currentColor"></path>
                    </svg>
                </div>
                <div class="relative z-10 space-y-4">
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                        Welcome back, {{ auth()->user()->name }}! 🎓
                    </h1>
                    <p class="text-emerald-100 text-lg max-w-xl">
                        Your students are waiting for you. Manage your schedule, review recent requests, and track your platform earnings.
                    </p>
                    @if (auth()->user()->tutorProfile && auth()->user()->tutorProfile->subjects->isNotEmpty())
                        <div class="flex flex-wrap gap-2 pt-1">
                            @foreach (auth()->user()->tutorProfile->subjects as $subj)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/20 text-white border border-white/10">
                                    {{ $subj->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    <div class="pt-2 flex flex-wrap gap-4">
                        @if (auth()->user()->tutorProfile)
                            <a href="{{ route('tutor.profile.show') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-xl text-teal-700 bg-white hover:bg-emerald-50 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-150">
                                View Profile
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </a>
                            <a href="{{ route('tutor.profile.edit') }}" class="inline-flex items-center justify-center px-5 py-3 border border-emerald-500 text-base font-medium rounded-xl text-white hover:bg-emerald-600/20 hover:-translate-y-0.5 transition-all duration-150">
                                Edit Profile
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('tutor.profile.create') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-xl text-teal-700 bg-white hover:bg-emerald-50 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-150">
                                Create Profile
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Earnings -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full">+12.4% this month</span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">$450.00</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Total Earnings</p>
                    </div>
                </div>

                <!-- Hours Taught -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-violet-50 text-violet-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-violet-600 bg-violet-50 px-2.5 py-0.5 rounded-full">This Term</span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">18.0 hrs</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Total Hours Taught</p>
                    </div>
                </div>

                <!-- Active Students -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-sky-50 text-sky-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full">Active</span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">5</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Active Students</p>
                    </div>
                </div>

                <!-- Rating -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-amber-50 text-amber-500">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full">12 Reviews</span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">4.9 / 5.0</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Average Rating</p>
                    </div>
                </div>
            </div>

            <!-- Session Feedback Alerts -->
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Content Area -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Upcoming Lessons / Requests -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 lg:col-span-2 space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-800">Recent Booking Requests</h2>
                        <a href="{{ route('tutor.bookings.index') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">Manage Bookings</a>
                    </div>
                    
                    <div class="divide-y divide-slate-100">
                        @forelse ($pendingBookings as $booking)
                            <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 first:pt-0 last:pb-0">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex-shrink-0 flex items-center justify-center font-bold text-lg">
                                        {{ strtoupper(substr($booking->learner->name ?? 'L', 0, 2)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800">{{ $booking->learner->name ?? 'Learner' }}</h4>
                                        <p class="text-sm text-slate-500">Wants: {{ $booking->subject->name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between sm:justify-end gap-4">
                                    <div class="text-left sm:text-right">
                                        <p class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($booking->session_date)->format('M d, Y') }}</p>
                                        <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($booking->session_time)->format('h:i A') }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <form action="{{ route('tutor.bookings.accept', $booking) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm hover:shadow transition">
                                                Accept
                                            </button>
                                        </form>
                                        <form action="{{ route('tutor.bookings.reject', $booking) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg transition">
                                                Decline
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-slate-500">No pending booking requests.</p>
                                <a href="{{ route('tutor.bookings.index') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold text-sm inline-block mt-2">Manage All Bookings</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions / Sidebar -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-6">
                    <h2 class="text-lg font-bold text-slate-800">Tutor Settings</h2>
                    <div class="grid grid-cols-1 gap-4">
                        @if (auth()->user()->tutorProfile)
                            <a href="{{ route('tutor.profile.edit') }}" class="flex items-center p-3 rounded-xl hover:bg-slate-50 border border-slate-100 transition-colors group">
                                <div class="p-2.5 rounded-lg bg-emerald-50 text-emerald-600 mr-4 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">Edit Tutor Profile</h4>
                                    <p class="text-xs text-slate-500">Update bio, hourly rate, photo</p>
                                </div>
                            </a>
                        @else
                            <a href="{{ route('tutor.profile.create') }}" class="flex items-center p-3 rounded-xl hover:bg-slate-50 border border-slate-100 transition-colors group">
                                <div class="p-2.5 rounded-lg bg-emerald-50 text-emerald-600 mr-4 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">Create Tutor Profile</h4>
                                    <p class="text-xs text-slate-500">Create profile to start teaching</p>
                                </div>
                            </a>
                        @endif

                        <a href="{{ route('tutor.subjects.edit') }}" class="flex items-center p-3 rounded-xl hover:bg-slate-50 border border-slate-100 transition-colors group">
                            <div class="p-2.5 rounded-lg bg-teal-50 text-teal-600 mr-4 group-hover:bg-teal-600 group-hover:text-white transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Manage Subjects</h4>
                                <p class="text-xs text-slate-500">Add or edit taught subjects</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-center p-3 rounded-xl hover:bg-slate-50 border border-slate-100 transition-colors group">
                            <div class="p-2.5 rounded-lg bg-violet-50 text-violet-600 mr-4 group-hover:bg-violet-600 group-hover:text-white transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Payout Settings</h4>
                                <p class="text-xs text-slate-500">Link Stripe or Bank Account</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>