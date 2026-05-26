<x-guest-layout>
    <!-- Navigation Header -->
    <header class="bg-white border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center space-x-2 text-indigo-600 font-extrabold text-xl tracking-tight">
                <span>SkillSwap</span>
            </a>
            <nav class="hidden md:flex space-x-8 text-sm font-semibold text-slate-600">
                <a href="{{ route('tutors.index') }}" class="text-slate-500 hover:text-indigo-600 transition">Find Tutors</a>
            </nav>
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ auth()->user()->dashboardUrl() }}" class="text-sm font-bold text-slate-700 hover:text-indigo-600 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition">Log in</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">Sign up</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Detailed Profile Content -->
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Back link -->
            <div class="flex items-center justify-between">
                <a href="{{ route('tutors.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-indigo-600 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    Back to Tutor Directory
                </a>
            </div>

            <!-- Profile Info Panel -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Photo, Stats, and Availability -->
                <div class="space-y-6">
                    <!-- Photo & Core Info -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-center space-y-6">
                        <div class="flex flex-col items-center space-y-4">
                            @if ($tutorProfile->profile_photo)
                                <img src="{{ Storage::url($tutorProfile->profile_photo) }}" alt="Tutor Photo" class="w-36 h-36 rounded-full object-cover shadow-md border-4 border-slate-50">
                            @else
                                <div class="w-36 h-36 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 shadow-inner border border-slate-100">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                            @endif
                            
                            <div>
                                <div class="flex items-center justify-center space-x-1.5">
                                    <h3 class="text-xl font-bold text-slate-800">{{ $tutorProfile->user->name }}</h3>
                                    @if ($tutorProfile->verification_status === 'verified')
                                        <span class="text-blue-500" title="Verified Tutor">
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M6.267 3.455a.75.75 0 00-.708-.523H4.5a2.5 2.5 0 00-2.5 2.5v1.059a.75.75 0 00.523.708L5.79 8.267c.365.122.523.513.354.858L4.655 12.18c-.147.294-.078.653.167.87l.79.79c.216.216.544.254.8.096l2.842-1.76a.75.75 0 011.025.267l1.76 2.842c.158.256.452.378.741.304l1.059-.268a.75.75 0 00.523-.708v-1.059a.75.75 0 00-.523-.708l-3.267-1.076a.75.75 0 01-.354-.858l1.49-3.056c.146-.294.077-.653-.168-.87l-.79-.79a.75.75 0 00-.8-.096l-2.842 1.76a.75.75 0 01-1.025-.267L6.267 3.455z"></path><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"></path></svg>
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-500 font-medium mt-0.5">{{ $tutorProfile->education }}</p>
                            </div>
                        </div>

                        <!-- Mini Metrics -->
                        <div class="grid grid-cols-2 gap-4 border-y border-slate-100 py-4 my-2">
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-semibold">Hourly Rate</p>
                                <p class="text-lg font-extrabold text-slate-800 mt-0.5">${{ number_format($tutorProfile->hourly_rate, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-semibold">Rating</p>
                                <div class="flex items-center justify-center space-x-1.5 mt-0.5">
                                    <div class="flex items-center text-amber-400">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-slate-800">
                                        {{ $averageRating ? number_format($averageRating, 1) : 'New' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Section -->
                        <div class="mt-4">
                            @guest
                                <div>
                                    <a href="{{ route('login') }}" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transition">
                                        Log in to Book a Lesson
                                    </a>
                                </div>
                            @else
                                @if(auth()->user()->role === 'learner')
                                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-100 space-y-4 text-left">
                                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-2">Book a Lesson</h3>
                                        
                                        <!-- Session Messages -->
                                        @if (session('success'))
                                            <div class="p-3 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-semibold">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                        @if (session('error'))
                                            <div class="p-3 rounded-lg bg-rose-50 border border-rose-100 text-rose-800 text-xs font-semibold">
                                                {{ session('error') }}
                                            </div>
                                        @endif

                                        <form action="{{ route('learner.bookings.store', $tutorProfile) }}" method="POST" class="space-y-4">
                                            @csrf

                                            <!-- Subject selection -->
                                            <div>
                                                <label for="subject_id" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Select Subject</label>
                                                <select name="subject_id" id="subject_id" required class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                                    <option value="" disabled selected>Choose a subject...</option>
                                                    @foreach ($tutorProfile->subjects as $subject)
                                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                            {{ $subject->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('subject_id')
                                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Date selection -->
                                            <div>
                                                <label for="session_date" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Session Date</label>
                                                <input type="date" name="session_date" id="session_date" min="{{ date('Y-m-d') }}" required value="{{ old('session_date') }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                                @error('session_date')
                                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Time selection -->
                                            <div>
                                                <label for="session_time" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Session Time</label>
                                                <input type="time" name="session_time" id="session_time" required value="{{ old('session_time') }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                                @error('session_time')
                                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Notes -->
                                            <div>
                                                <label for="notes" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Optional Notes</label>
                                                <textarea name="notes" id="notes" rows="3" placeholder="Tell the tutor what you want to focus on..." class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">{{ old('notes') }}</textarea>
                                                @error('notes')
                                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transition">
                                                Request Booking
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-sm text-slate-500 italic">
                                        Only learners can request lesson bookings.
                                    </div>
                                @endif
                            @endguest
                        </div>
                    </div>

                    <!-- Availability Slots Preview -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Availability Preview</h3>
                        <div class="divide-y divide-slate-50 text-sm">
                            @forelse ($tutorProfile->availabilitySlots->where('is_available', true) as $slot)
                                <div class="flex items-center justify-between py-2.5 first:pt-0 last:pb-0">
                                    <span class="font-semibold text-slate-700">{{ $slot->day }}</span>
                                    <span class="text-slate-500">
                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic">No availability slots configured.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Column: Biography, Education, Experience, Subjects, Reviews -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 lg:col-span-2 space-y-8 self-start">
                    <!-- Bio Section -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Biography</h3>
                        <p class="text-slate-600 leading-relaxed whitespace-pre-line text-sm">
                            {{ $tutorProfile->bio }}
                        </p>
                    </div>

                    <!-- Subjects Taught -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Subjects Taught</h3>
                        <div class="flex flex-wrap gap-2 py-1">
                            @forelse ($tutorProfile->subjects as $subject)
                                <span class="px-3 py-1 rounded-xl text-sm font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $subject->name }}
                                </span>
                            @empty
                                <span class="text-sm text-slate-400 italic">General Studies</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Credentials / Education -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Education & Credentials</h3>
                        <div class="flex items-start space-x-3 bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm">
                            <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600 mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">Qualifications</h4>
                                <p class="text-slate-600 mt-1">{{ $tutorProfile->education }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Experience -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Experience</h3>
                        <div class="flex items-start space-x-3 bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm">
                            <div class="p-2 rounded-lg bg-violet-50 text-violet-600 mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">Teaching History</h4>
                                <p class="text-slate-600 mt-1">{{ $tutorProfile->experience }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">
                            Student Reviews ({{ $reviewsCount }})
                        </h3>
                        <div class="divide-y divide-slate-100 text-sm">
                            @forelse ($tutorProfile->reviews as $review)
                                <div class="py-4 first:pt-0 last:pb-0 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-bold text-slate-800">{{ $review->learner->name }}</h4>
                                        <span class="text-xs text-slate-400">{{ $review->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <!-- Stars -->
                                    <div class="flex items-center text-amber-400">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 fill-current {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endfor
                                    </div>
                                    <!-- Comment -->
                                    <p class="text-slate-600 leading-relaxed">{{ $review->comment }}</p>
                                </div>
                            @empty
                                <p class="text-slate-400 italic">No reviews have been posted for this tutor yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-guest-layout>
