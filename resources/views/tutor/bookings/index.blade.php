<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Booking Requests & Schedule') }}
            </h2>
            <span class="px-3 py-1 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full">
                Guide Portal
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
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

            <!-- Bookings List -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">All Session Bookings</h3>
                    <p class="text-sm text-slate-500">Accept incoming requests, track scheduled sessions, and mark completed sessions.</p>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($bookings as $booking)
                        <div class="p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-6 hover:bg-slate-50/50 transition">
                            <!-- Left: Learner and subject info -->
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 rounded-xl bg-emerald-50 text-emerald-600 flex-shrink-0 flex items-center justify-center font-bold text-xl shadow-inner border border-emerald-100/50">
                                    {{ strtoupper(substr($booking->learner->name ?? 'L', 0, 2)) }}
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-slate-800 text-base">{{ $booking->learner->name ?? 'Learner' }}</h4>
                                    <p class="text-sm text-emerald-600 font-semibold mt-0.5">{{ $booking->subject->name }}</p>
                                    @if ($booking->notes)
                                        <p class="text-xs text-slate-500 mt-1 max-w-xl bg-slate-50 p-2 rounded-lg border border-slate-100"><span class="font-bold">Student Notes:</span> {{ $booking->notes }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Right: Date, Time, Status, and Actions -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between lg:justify-end gap-6">
                                <!-- Date & Time -->
                                <div class="text-left sm:text-right">
                                    <p class="text-sm font-extrabold text-slate-700 flex items-center sm:justify-end gap-1.5">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($booking->session_date)->format('M d, Y') }}
                                    </p>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5 flex items-center sm:justify-end gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($booking->session_time)->format('h:i A') }}
                                    </p>
                                </div>

                                <!-- Badge & Actions -->
                                <div class="flex items-center space-x-4">
                                    @if ($booking->status === 'confirmed')
                                        <span class="px-3 py-1 text-xs font-bold text-emerald-700 bg-emerald-100 rounded-full border border-emerald-200">Confirmed</span>
                                    @elseif ($booking->status === 'pending')
                                        <span class="px-3 py-1 text-xs font-bold text-amber-700 bg-amber-100 rounded-full border border-amber-200">Pending Approval</span>
                                    @elseif ($booking->status === 'completed')
                                        <span class="px-3 py-1 text-xs font-bold text-indigo-700 bg-indigo-100 rounded-full border border-indigo-200">Completed</span>
                                    @elseif ($booking->status === 'cancelled')
                                        <span class="px-3 py-1 text-xs font-bold text-rose-700 bg-rose-100 rounded-full border border-rose-200">Cancelled</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold text-slate-700 bg-slate-100 rounded-full border border-slate-200">{{ ucfirst($booking->status) }}</span>
                                    @endif

                                    <!-- Actions block -->
                                    <div class="flex items-center gap-2">
                                        @if ($booking->status === 'pending')
                                            <form action="{{ route('tutor.bookings.accept', $booking) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="px-3.5 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm hover:shadow transition">
                                                    Accept
                                                </button>
                                            </form>
                                            <form action="{{ route('tutor.bookings.reject', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to decline this booking?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="px-3.5 py-1.5 text-xs font-bold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl transition">
                                                    Decline
                                                </button>
                                            </form>
                                        @elseif ($booking->status === 'confirmed')
                                            <form action="{{ route('tutor.bookings.complete', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Mark this session as completed?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="px-3.5 py-1.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm hover:shadow transition">
                                                    Mark Completed
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 px-6">
                            <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h4 class="font-extrabold text-slate-800 text-lg">No sessions yet</h4>
                            <p class="text-slate-500 text-sm mt-1 max-w-sm mx-auto">No learners have booked session requests yet. Make sure your guide profile bio and rates are up to date!</p>
                        </div>
                    @endforelse
                </div>

                @if ($bookings->hasPages())
                    <div class="p-6 bg-slate-50 border-t border-slate-100">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
