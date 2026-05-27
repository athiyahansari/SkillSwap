<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Learner Dashboard') }}
            </h2>
            <span class="px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full">
                Learner Portal
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Header -->
            <div class="bg-gradient-to-r from-indigo-600 via-indigo-700 to-violet-800 rounded-3xl p-8 md:p-12 shadow-xl relative overflow-hidden text-white">
                <div class="absolute right-0 bottom-0 top-0 w-1/3 opacity-10 pointer-events-none">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0,100 C30,40 70,60 100,0 L100,100 Z" fill="currentColor"></path>
                    </svg>
                </div>
                <div class="relative z-10 space-y-4">
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                        Welcome back, {{ auth()->user()->name }}! 👋
                    </h1>
                    <p class="text-indigo-100 text-lg max-w-xl">
                        Ready to acquire new skills today? Explore thousands of top-rated tutors and schedules tailored to you.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('tutors.index') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-xl text-indigo-700 bg-white hover:bg-indigo-50 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-150">
                            Explore Subjects
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Hours Learned -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full">+2 hrs this week</span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">14.5 hrs</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Total Time Learned</p>
                    </div>
                </div>

                <!-- Upcoming Bookings -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-violet-50 text-violet-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-violet-600 bg-violet-50 px-2.5 py-0.5 rounded-full">Next: Tomorrow</span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">{{ $upcomingBookings->count() }}</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Upcoming Lessons</p>
                    </div>
                </div>

                <!-- Connected Tutors -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-sky-50 text-sky-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full">Active</span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">3</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Active Tutors</p>
                    </div>
                </div>

                <!-- Wallet Credits -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-amber-50 text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-0.5 rounded-full">Refill</span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">$120.00</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Wallet Balance</p>
                    </div>
                </div>
            </div>

            <!-- Content Area: Bookings and Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Bookings Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 lg:col-span-2 space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-800">Your Recent & Upcoming Lessons</h2>
                        <a href="{{ route('learner.bookings.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">View All</a>
                    </div>
                    
                    <div class="divide-y divide-slate-100">
                        @forelse ($upcomingBookings as $booking)
                            <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 first:pt-0 last:pb-0">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex-shrink-0 flex items-center justify-center font-bold text-lg">
                                        {{ strtoupper(substr($booking->tutorProfile->user->name ?? 'T', 0, 2)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800">{{ $booking->tutorProfile->user->name ?? 'Tutor' }}</h4>
                                        <p class="text-sm text-slate-500">{{ $booking->subject->name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between sm:justify-end gap-6">
                                    <div class="text-left sm:text-right">
                                        <p class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($booking->session_date)->format('M d, Y') }}</p>
                                        <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($booking->session_time)->format('h:i A') }}</p>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        @if ($booking->status === 'confirmed')
                                            <span class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full">Confirmed</span>
                                        @elseif ($booking->status === 'pending')
                                            <span class="px-2.5 py-1 text-xs font-semibold text-amber-700 bg-amber-50 rounded-full">Pending</span>
                                            <form action="{{ route('learner.bookings.cancel', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="px-2.5 py-1 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-full border border-rose-200 transition">
                                                    Cancel Booking
                                                </button>
                                            </form>
                                        @elseif ($booking->status === 'completed')
                                            <span class="px-2.5 py-1 text-xs font-semibold text-indigo-700 bg-indigo-50 rounded-full border border-indigo-100">Completed</span>
                                            @if (!$booking->review)
                                                <a href="{{ route('learner.reviews.create', $booking) }}" class="px-2.5 py-1 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-full transition">
                                                    Leave Review
                                                </a>
                                            @else
                                                <div class="flex items-center text-amber-500 text-xs font-semibold">
                                                    <svg class="w-3.5 h-3.5 fill-current mr-1" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                    Rated {{ $booking->review->rating }}/5
                                                </div>
                                            @endif
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold text-slate-700 bg-slate-50 rounded-full">{{ ucfirst($booking->status) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-slate-500">No recent or upcoming lessons scheduled.</p>
                                <a href="{{ route('tutors.index') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold text-sm inline-block mt-2">Find a Tutor</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions / Sidebar -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-6">
                    <h2 class="text-lg font-bold text-slate-800">Quick Actions</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <a href="{{ route('tutors.index') }}" class="flex items-center p-3 rounded-xl hover:bg-slate-50 border border-slate-100 transition-colors group">
                            <div class="p-2.5 rounded-lg bg-indigo-50 text-indigo-600 mr-4 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Find new Tutors</h4>
                                <p class="text-xs text-slate-500">Browse categories & reviews</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-center p-3 rounded-xl hover:bg-slate-50 border border-slate-100 transition-colors group">
                            <div class="p-2.5 rounded-lg bg-violet-50 text-violet-600 mr-4 group-hover:bg-violet-600 group-hover:text-white transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Messages</h4>
                                <p class="text-xs text-slate-500">Contact active tutors</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-center p-3 rounded-xl hover:bg-slate-50 border border-slate-100 transition-colors group">
                            <div class="p-2.5 rounded-lg bg-emerald-50 text-emerald-600 mr-4 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Help & Support</h4>
                                <p class="text-xs text-slate-500">Read FAQs or open a ticket</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>