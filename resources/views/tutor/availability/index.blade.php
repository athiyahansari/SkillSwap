<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Manage Your Availability') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Success / Error Alert Messages -->
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium shadow-sm space-y-1">
                    <div class="flex items-center text-rose-700 font-bold mb-1">
                        <svg class="w-5 h-5 mr-2 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Please fix the errors below:
                    </div>
                    <ul class="list-disc list-inside text-xs text-rose-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Add Availability Form Column -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 self-start space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Add Availability Slot</h3>
                        <p class="text-xs text-slate-500 mt-1">Specify a day and time range when you are free to conduct learning sessions.</p>
                    </div>

                    <form method="POST" action="{{ route('tutor.availability.store') }}" class="space-y-4">
                        @csrf

                        <!-- Day of Week -->
                        <div>
                            <label for="day" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Day of Week</label>
                            <select name="day" id="day" required class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <option value="" disabled selected>Select a day...</option>
                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $dayOfWeek)
                                    <option value="{{ $dayOfWeek }}" {{ old('day') == $dayOfWeek ? 'selected' : '' }}>
                                        {{ $dayOfWeek }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Start Time -->
                        <div>
                            <label for="start_time" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Start Time</label>
                            <input type="time" name="start_time" id="start_time" required value="{{ old('start_time') }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        </div>

                        <!-- End Time -->
                        <div>
                            <label for="end_time" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">End Time</label>
                            <input type="time" name="end_time" id="end_time" required value="{{ old('end_time') }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        </div>

                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm hover:shadow transition-all duration-150">
                            Add Slot
                        </button>
                    </form>
                </div>

                <!-- Availability List Column -->
                <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Your Weekly Schedule</h3>
                            <p class="text-xs text-slate-500 mt-1">These slots will be displayed on your public profile. Learners will be able to book sessions within these times.</p>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                            {{ $slots->count() }} {{ Str::plural('Slot', $slots->count()) }}
                        </span>
                    </div>

                    @if ($slots->isEmpty())
                        <div class="text-center py-12 border-2 border-dashed border-slate-100 rounded-xl">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-slate-500 text-sm font-medium">No availability slots set yet.</p>
                            <p class="text-slate-400 text-xs mt-1">Use the form on the left to define your first available hours.</p>
                        </div>
                    @else
                        <div class="divide-y divide-slate-100">
                            @foreach ($slots as $slot)
                                <div class="py-3.5 flex items-center justify-between group">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                            {{ substr($slot->day, 0, 3) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800 text-sm">{{ $slot->day }}</h4>
                                            <p class="text-xs text-slate-500 mt-0.5">
                                                {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <form action="{{ route('tutor.availability.destroy', $slot) }}" method="POST" class="opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors" title="Delete Slot">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex items-center justify-end border-t border-slate-100 pt-6">
                        <a href="{{ route('tutor.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
