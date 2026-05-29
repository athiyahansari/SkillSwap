<div>
    <!-- Marketplace Content -->
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Hero / Banner -->
            <div class="text-center py-6">
                <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight">Find Your Skill Guide</h1>
                <p class="mt-3 text-slate-500 text-lg max-w-2xl mx-auto">Get quick, peer-to-peer help with code, design, essays, and more. Book a 1-on-1 session to solve that tricky problem.</p>
            </div>

            <!-- Search & Filters Panel -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Input -->
                    <div>
                        <label for="search" class="sr-only">Search</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="search" id="search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-xl" placeholder="Search by tutor name...">
                        </div>
                    </div>

                    <!-- Subject Dropdown Filter -->
                    <div>
                        <label for="subject" class="sr-only">Subject</label>
                        <select wire:model.live="subject" id="subject" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-xl">
                            <option value="">All Skills / Topics</option>
                            @foreach ($allSubjects as $subjectItem)
                                <option value="{{ $subjectItem->id }}">{{ $subjectItem->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Clear Buttons (Optional but good for UX) -->
                    <div class="flex space-x-3 items-center">
                        <!-- Loading Indicator for better UX without full page reload -->
                        <div wire:loading class="text-indigo-600 text-sm font-semibold animate-pulse">
                            Searching...
                        </div>

                        @if(!empty($search) || !empty($subject))
                            <button wire:click="clearFilters" class="ml-auto inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition">
                                Clear
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tutors Grid -->
            @if ($tutors->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 relative" wire:loading.class="opacity-50 transition-opacity">
                    @foreach ($tutors as $tutor)
                        <div wire:key="tutor-{{ $tutor->id }}" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col hover:shadow-md hover:-translate-y-1 transition duration-300">
                            <!-- Card Header Info -->
                            <div class="p-6 flex items-start space-x-4 border-b border-slate-50">
                                @if ($tutor->profile_photo)
                                    <img src="{{ $tutor->profile_photo_url }}" alt="Tutor Photo" class="w-16 h-16 rounded-full object-cover shadow-sm">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-slate-100 border border-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-1.5">
                                        <h2 class="font-bold text-slate-800 text-lg truncate">{{ $tutor->user->name }}</h2>
                                        @if ($tutor->verification_status === 'verified')
                                            <span class="text-blue-500" title="Verified Skill Guide (Proof of Expertise)">
                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M6.267 3.455a.75.75 0 00-.708-.523H4.5a2.5 2.5 0 00-2.5 2.5v1.059a.75.75 0 00.523.708L5.79 8.267c.365.122.523.513.354.858L4.655 12.18c-.147.294-.078.653.167.87l.79.79c.216.216.544.254.8.096l2.842-1.76a.75.75 0 011.025.267l1.76 2.842c.158.256.452.378.741.304l1.059-.268a.75.75 0 00.523-.708v-1.059a.75.75 0 00-.523-.708l-3.267-1.076a.75.75 0 01-.354-.858l1.49-3.056c.146-.294.077-.653-.168-.87l-.79-.79a.75.75 0 00-.8-.096l-2.842 1.76a.75.75 0 01-1.025-.267L6.267 3.455z"></path><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"></path></svg>
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm font-semibold text-slate-500 mt-0.5 truncate">{{ $tutor->education }}</p>
                                    
                                    <!-- Rating -->
                                    <div class="flex items-center space-x-1.5 mt-1.5">
                                        <div class="flex items-center text-amber-400">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        </div>
                                        <span class="text-sm font-bold text-slate-700">
                                            {{ $tutor->reviews->isNotEmpty() ? number_format($tutor->reviews->avg('rating'), 1) : 'New' }}
                                        </span>
                                        <span class="text-xs text-slate-400">
                                            ({{ $tutor->reviews->count() }} {{ \Illuminate\Support\Str::plural('review', $tutor->reviews->count()) }})
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body (Bio & Subjects) -->
                            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                                <p class="text-sm text-slate-500 line-clamp-3 leading-relaxed">{{ $tutor->bio }}</p>

                                <div class="space-y-3">
                                    <!-- Subjects pills -->
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse ($tutor->subjects->take(3) as $subject)
                                            <span class="px-2 py-0.5 rounded-lg text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                {{ $subject->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs italic text-slate-400">General Skills</span>
                                        @endforelse
                                        @if ($tutor->subjects->count() > 3)
                                            <span class="px-2 py-0.5 rounded-lg text-xs font-medium bg-slate-50 text-slate-600 border border-slate-100">
                                                +{{ $tutor->subjects->count() - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer (Rate & Link) -->
                            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Session Rate</span>
                                    <span class="text-lg font-extrabold text-slate-800">${{ number_format($tutor->hourly_rate, 2) }}<span class="text-xs text-slate-500 font-medium">/hr</span></span>
                                </div>
                                <a href="{{ route('tutors.show', $tutor->id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="pt-6">
                    {{ $tutors->links() }}
                </div>
            @else
                <!-- No Results Empty State -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center max-w-xl mx-auto">
                    <div class="mx-auto w-12 h-12 text-indigo-500 mb-4">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">No guides found</h3>
                    <p class="text-sm text-slate-500 mt-2">We couldn't find any guides matching your search or filters. Try adjusting your query or resetting the filters.</p>
                    <button wire:click="clearFilters" class="inline-flex items-center justify-center mt-4 px-4 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition">
                        Reset Filters
                    </button>
                </div>
            @endif

        </div>
    </div>
</div>
