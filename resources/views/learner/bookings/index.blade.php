<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('My Bookings') }}
            </h2>
            <a href="{{ route('tutors.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                Find Skill Guides
            </a>
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
                    <h3 class="text-lg font-bold text-slate-800">Booking History</h3>
                    <p class="text-sm text-slate-500">View and manage your skill sharing sessions.</p>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($bookings as $booking)
                        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:bg-slate-50/50 transition">
                            <!-- Left block: Tutor info -->
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 rounded-xl bg-indigo-50 text-indigo-600 flex-shrink-0 flex items-center justify-center font-bold text-xl shadow-inner border border-indigo-100/50">
                                    {{ strtoupper(substr($booking->tutorProfile->user->name ?? 'T', 0, 2)) }}
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-slate-800 text-base">{{ $booking->tutorProfile->user->name ?? 'Guide' }}</h4>
                                    <p class="text-sm text-indigo-600 font-semibold mt-0.5">{{ $booking->subject->name }}</p>
                                    @if ($booking->notes)
                                        <p class="text-xs text-slate-500 mt-1 max-w-xl bg-slate-50 p-2 rounded-lg border border-slate-100"><span class="font-bold">Notes:</span> {{ $booking->notes }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Right block: Schedule, status & actions -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between md:justify-end gap-6">
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

                                <!-- Status Badge -->
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

                                    @if ($booking->payment_status === 'paid')
                                        <span class="px-3 py-1 text-xs font-bold text-emerald-700 bg-emerald-100 rounded-full border border-emerald-200">Paid</span>
                                    @endif

                                    <!-- Actions -->
                                    @if ($booking->status === 'confirmed' && $booking->payment_status === 'unpaid')
                                        <form action="{{ route('learner.bookings.pay', $booking) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3.5 py-1.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                                Pay Now
                                            </button>
                                        </form>
                                    @elseif ($booking->status === 'pending')
                                        <form action="{{ route('learner.bookings.cancel', $booking) }}" method="POST" data-confirm="Are you sure you want to cancel this booking?">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="px-3.5 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition">
                                                Cancel Booking
                                            </button>
                                        </form>
                                    @elseif ($booking->status === 'completed')
                                        @if (!$booking->review)
                                            <a href="{{ route('learner.reviews.create', $booking) }}" class="px-3.5 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl transition">
                                                Leave Review
                                            </a>
                                        @else
                                            <div class="flex items-center text-amber-500 text-xs font-semibold">
                                                <svg class="w-3.5 h-3.5 fill-current mr-1" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                Rated {{ $booking->review->rating }}/5
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 px-6">
                            <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h4 class="font-extrabold text-slate-800 text-lg">No bookings found</h4>
                            <p class="text-slate-500 text-sm mt-1 max-w-sm mx-auto">You haven't requested any sessions yet. Browse skill guides and start booking today!</p>
                            <a href="{{ route('tutors.index') }}" class="inline-flex items-center px-4 py-2 mt-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                                Browse Skill Guides
                            </a>
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
