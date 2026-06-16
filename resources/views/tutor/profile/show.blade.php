<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('My Guide Profile') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('tutor.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm">
                    Back to Dashboard
                </a>
                <a href="{{ route('tutor.availability.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm">
                    Manage Availability
                </a>
                <a href="{{ route('tutor.profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-sm">
                    Edit Profile
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash & Status Messages -->
            @if (session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-start space-x-3">
                    <div class="text-emerald-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-xl shadow-sm flex items-start space-x-3">
                    <div class="text-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-blue-800">{{ session('info') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm flex items-start space-x-3">
                    <div class="text-rose-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-rose-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Profile Info Panel -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Side: Profile Photo & Key Info Card & Availability Card -->
                <div class="space-y-6 self-start">
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden p-6 space-y-6 text-center">
                        <div class="flex flex-col items-center space-y-4">
                            <!-- Profile Image Container -->
                            @if ($profile->profile_photo)
                                <img src="{{ $profile->profile_photo_url }}" alt="Tutor Photo" class="w-36 h-36 rounded-full object-cover shadow-md border-4 border-slate-50">
                            @else
                                <div class="w-36 h-36 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 shadow-inner border border-slate-100">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                            @endif
                            
                            <div>
                                <h3 class="text-xl font-bold text-slate-800">{{ auth()->user()->name }}</h3>
                                <p class="text-sm text-slate-500 font-medium">Skill Guide</p>
                            </div>
                        </div>

                        <!-- Stats / Mini Metrics -->
                        <div class="grid grid-cols-2 gap-4 border-y border-slate-100 py-4 my-2">
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-semibold">Session Rate</p>
                                <p class="text-lg font-bold text-slate-800 mt-0.5">${{ number_format($profile->hourly_rate, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-semibold">Status</p>
                                @if ($profile->verification_status === 'verified')
                                    <span class="inline-flex mt-1.5 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full">
                                        Verified
                                    </span>
                                @elseif ($profile->verification_status === 'rejected')
                                    <span class="inline-flex mt-1.5 px-2.5 py-0.5 text-xs font-semibold text-rose-700 bg-rose-100 rounded-full">
                                        Rejected
                                    </span>
                                @else
                                    <span class="inline-flex mt-1.5 px-2.5 py-0.5 text-xs font-semibold text-amber-700 bg-amber-100 rounded-full">
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="text-left space-y-4 text-sm">
                            <div>
                                <h4 class="font-bold text-slate-700">Account Details</h4>
                                <p class="text-slate-500 mt-1"><span class="font-medium text-slate-600">Email:</span> {{ auth()->user()->email }}</p>
                                <p class="text-slate-500"><span class="font-medium text-slate-600">Member Since:</span> {{ auth()->user()->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Availability Slots Preview Card -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">My Availability</h3>
                            <a href="{{ route('tutor.availability.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                                Edit
                            </a>
                        </div>
                        <div class="divide-y divide-slate-50 text-sm">
                            @forelse ($profile->availabilitySlots->where('is_available', true) as $slot)
                                <div class="flex items-center justify-between py-2.5 first:pt-0 last:pb-0">
                                    <span class="font-semibold text-slate-700">{{ $slot->day }}</span>
                                    <span class="text-slate-500">
                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-4 space-y-2">
                                    <p class="text-xs text-slate-400 italic">No availability slots configured.</p>
                                    <a href="{{ route('tutor.availability.index') }}" class="inline-flex items-center px-3 py-1.5 border border-slate-200 text-xs font-semibold rounded-lg text-slate-700 bg-slate-50 hover:bg-slate-100 transition shadow-sm">
                                        Set Availability Slots
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Side: Extensive Bio, Education, Experience Details -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 lg:col-span-2 space-y-8">
                    <!-- Bio Section -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Biography</h3>
                        <p class="text-slate-600 leading-relaxed whitespace-pre-line text-sm">
                            {{ $profile->bio }}
                        </p>
                    </div>

                    <!-- Subjects Section -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Subjects Taught</h3>
                        @if ($profile->subjects->isNotEmpty())
                            <div class="flex flex-wrap gap-2 py-1">
                                @foreach ($profile->subjects as $subject)
                                    <span class="px-3 py-1 rounded-xl text-sm font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm hover:shadow-md transition">
                                        {{ $subject->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-500 italic">No subjects selected yet. <a href="{{ route('tutor.subjects.edit') }}" class="text-indigo-600 font-semibold hover:underline">Manage Subjects</a> to select the topics you teach.</p>
                        @endif
                    </div>

                    <!-- Education Section -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Background & Expertise</h3>
                        <div class="flex items-start space-x-3 bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm">
                            <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600 mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">Background & Expertise</h4>
                                <p class="text-slate-600 mt-1">{{ $profile->education }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Experience Section -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Helping Experience</h3>
                        <div class="flex items-start space-x-3 bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm">
                            <div class="p-2 rounded-lg bg-violet-50 text-violet-600 mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">Background & Helping History</h4>
                                <p class="text-slate-600 mt-1">{{ $profile->experience }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
