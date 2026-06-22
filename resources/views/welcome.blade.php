<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SkillSwap - Peer-to-Peer Micro-Learning Marketplace</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif

        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Plus Jakarta Sans', 'sans-serif'],
                            outfit: ['Outfit', 'sans-serif'],
                        }
                    }
                }
            }
        </script>

        <style>
            html {
                font-size: 14.5px;
            }
            .scrollbar-none::-webkit-scrollbar {
                display: none;
            }
            .scrollbar-none {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
            .hero-glow {
                background: radial-gradient(circle 800px at 50% -100px, rgba(99, 102, 241, 0.08), transparent 80%);
            }
        </style>
    </head>
    <body class="bg-[#F8FAFC] text-slate-900 min-h-screen flex flex-col font-sans antialiased">
        <!-- Header / Navigation -->
        @livewire('navigation-menu')

        <!-- Main Content -->
        <main class="flex-grow">
            <!-- Hero / Search Section -->
            <section class="relative py-20 lg:py-28 bg-white hero-glow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-8">
                    <!-- New Semester Capsule -->
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/50 shadow-sm uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <span>New Semester Ready</span>
                    </div>

                    <!-- Heading -->
                    <div class="space-y-4 max-w-4xl mx-auto">
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 font-outfit leading-[1.15]">
                            Ace Your Exams with <br class="hidden sm:inline" />
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-amber-500">Peer Tutors</span>
                        </h1>
                        <p class="text-base sm:text-lg text-slate-500 max-w-2xl mx-auto leading-relaxed">
                            Connect with friendly peer guides who can help you solve tricky homework problems, review presentation decks, design with Figma, or debug code in real time.
                        </p>
                    </div>

                    <!-- Search Form -->
                    <div class="max-w-2xl mx-auto px-2">
                        <form action="{{ route('tutors.index') }}" method="GET" class="bg-white shadow-xl hover:shadow-2xl rounded-full p-2 flex flex-col sm:flex-row items-center border border-slate-100/80 transition duration-300 group">
                            <div class="flex-grow flex items-center px-4 w-full">
                                <svg class="w-5 h-5 text-slate-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text" name="search" id="search-input" class="w-full border-0 focus:ring-0 text-slate-800 placeholder-slate-400 bg-transparent text-sm py-2" placeholder="Search for Calculus...">
                            </div>
                            <!-- Custom Dropdown -->
                            @php
                                $featuredSlugs = ['python-programming', 'figma-basics', 'mathematics', 'canva-design', 'resume-review'];
                                $dropdownSubjects = $allSubjects->filter(fn($s) => in_array($s->slug, $featuredSlugs));
                            @endphp
                            <div class="relative w-full sm:w-48 flex-shrink-0 border-t sm:border-t-0 sm:border-l border-slate-100 p-2 sm:p-0 flex items-center px-4">
                                <svg class="w-5 h-5 text-slate-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                
                                <button type="button" id="dropdown-toggle" class="w-full text-left text-sm font-semibold text-slate-700 bg-transparent py-2 flex items-center justify-between focus:outline-none">
                                    <span id="dropdown-selected-label">All Skills</span>
                                    <svg class="w-4 h-4 text-slate-400 ml-1 transform transition-transform duration-200" id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <input type="hidden" name="subject" id="dropdown-value" value="">

                                <div id="dropdown-menu" class="hidden absolute top-full left-0 right-0 sm:right-auto sm:w-56 mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl py-2 z-50 transform opacity-0 scale-95 transition-all duration-200 origin-top-right">
                                    <button type="button" data-value="" class="dropdown-item w-full text-left px-4 py-2.5 text-sm font-medium text-indigo-600 hover:bg-indigo-50/50 transition flex items-center justify-between">
                                        <span>All Skills</span>
                                        <span class="checkmark text-indigo-600 font-bold">✓</span>
                                    </button>
                                    @foreach($dropdownSubjects as $sub)
                                        <button type="button" data-value="{{ $sub->id }}" class="dropdown-item w-full text-left px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-indigo-50/50 hover:text-indigo-600 transition flex items-center justify-between">
                                            <span>{{ $sub->name }}</span>
                                            <span class="checkmark hidden text-indigo-600 font-bold">✓</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-full transition shadow-md hover:shadow-lg flex-shrink-0 text-sm mt-2 sm:mt-0">
                                Find Tutors
                            </button>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Why Choose SkillSwap Section -->
            <section class="py-20 bg-[#F8FAFC] border-t border-slate-100/55">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
                    <div class="text-center space-y-3">
                        <h2 class="text-3xl font-extrabold text-slate-900 font-outfit tracking-tight">Why Choose SkillSwap?</h2>
                        <p class="text-slate-500 max-w-xl mx-auto text-sm sm:text-base">We make it safe and easy to find the perfect peer tutor.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Card 1 -->
                        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-5 hover:shadow-md transition duration-300">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <h3 class="font-extrabold text-lg text-slate-800 font-outfit">Verified Knowledge</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">
                                Every tutor is vetted for academic excellence. We check transcripts and proof of expertise so you don't have to.
                            </p>
                        </div>
                        <!-- Card 2 -->
                        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-5 hover:shadow-md transition duration-300">
                            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="font-extrabold text-lg text-slate-800 font-outfit">Flexible Scheduling</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">
                                Book sessions that fit your busy student life. Need a late-night study session or last-minute homework guide? No problem.
                            </p>
                        </div>
                        <!-- Card 3 -->
                        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-5 hover:shadow-md transition duration-300">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-extrabold text-lg text-slate-800 font-outfit">Affordable Rates</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">
                                Quality tutoring that doesn't break the bank. Peer rates are set by students, often costing 50% less than professional agencies.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Top Rated Tutors Section -->
            <section class="py-20 bg-white border-t border-slate-100/55">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                    <div class="flex items-end justify-between">
                        <div class="space-y-2">
                            <h2 class="text-3xl font-extrabold text-slate-900 font-outfit tracking-tight">Top Rated Tutors</h2>
                            <p class="text-slate-500 text-sm sm:text-base">Find the best help in your area</p>
                        </div>
                        <a href="{{ route('tutors.index') }}" class="text-indigo-600 hover:text-indigo-700 font-bold text-sm inline-flex items-center group transition">
                            <span>View all tutors</span>
                            <svg class="ml-1.5 w-4 h-4 transform group-hover:translate-x-1 transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Horizontal Scroll Container -->
                    <div class="flex overflow-x-auto gap-6 pb-6 pt-2 scrollbar-none snap-x snap-mandatory" style="-webkit-overflow-scrolling: touch;">
                        @forelse($topTutors as $tutor)
                            @php
                                $rating = number_format($tutor->reviews_avg_rating ?? (4.5 + ($tutor->id % 6) * 0.1), 1);
                                $school = strtoupper(explode(' ', $tutor->education)[0] ?? 'VERIFIED');
                                if (in_array($school, ['A-LEVEL', 'HIGH', 'SELF-TAUGHT', 'CERTIFIED', 'MUSIC'])) {
                                    $school = 'PEER';
                                }
                            @endphp
                            <div class="flex-shrink-0 w-80 bg-white rounded-3xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition duration-300 relative snap-start flex flex-col justify-between">
                                <!-- Top Row: School Badge & Rating -->
                                <div class="flex items-center justify-between w-full">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-slate-50 text-slate-500 uppercase tracking-wider">
                                        {{ $school }}
                                    </span>
                                    <div class="flex items-center space-x-1 bg-amber-50 text-amber-700 px-2 py-0.5 rounded-lg text-xs font-bold">
                                        <span>★</span>
                                        @if($tutor->reviews_count > 0)
                                            <span>{{ $rating }} ({{ $tutor->reviews_count }})</span>
                                        @else
                                            <span>{{ $rating }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Centered Avatar -->
                                <div class="my-6 flex justify-center">
                                    <div class="w-32 h-32 rounded-full overflow-hidden bg-slate-50 flex items-center justify-center p-3 border border-slate-100">
                                        <img src="{{ $tutor->profile_photo_url ?? asset('images/avatars/avatar-1.svg') }}" alt="{{ $tutor->user->name }}" class="w-full h-full object-contain">
                                    </div>
                                </div>

                                <!-- Meta Info -->
                                <div class="space-y-2 text-left">
                                    <div class="flex items-baseline justify-between">
                                        <h3 class="font-extrabold text-lg text-slate-800 font-outfit truncate pr-2">{{ $tutor->user->name }}</h3>
                                        <span class="font-bold text-indigo-600 text-sm whitespace-nowrap">${{ number_format($tutor->hourly_rate, 0) }}<span class="text-[10px] text-slate-400 font-medium">/hr</span></span>
                                    </div>
                                    <p class="text-xs text-slate-400 font-medium truncate">
                                        {{ $tutor->subjects->pluck('name')->join(' & ') ?: 'General Skills' }}
                                    </p>
                                </div>

                                <!-- Bottom Badges -->
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @if($tutor->verification_status === 'verified')
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-indigo-50 text-indigo-700 uppercase tracking-wider">
                                            Verified
                                        </span>
                                    @endif
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-600 uppercase tracking-wider truncate max-w-[150px]">
                                        {{ Str::limit($tutor->education, 18) }}
                                    </span>
                                </div>

                                <!-- Button -->
                                <div class="mt-6">
                                    <a href="{{ route('tutors.show', $tutor->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-100 text-xs font-bold text-slate-700 rounded-xl transition duration-150">
                                        View Profile
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-400 text-center w-full py-8 text-sm">No tutors available yet. Be the first to register!</p>
                        @endforelse
                    </div>
                </div>
            </section>

            <!-- Testimonial & Call To Action Section -->
            <section class="py-20 bg-[#F8FAFC] border-t border-slate-100/55">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="bg-gradient-to-tr from-slate-50 to-indigo-50/40 rounded-[32px] border border-slate-100 p-8 lg:p-12 shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                        <!-- Left Info & Testimonial -->
                        <div class="lg:col-span-7 space-y-8">
                            <div class="space-y-4">
                                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-outfit tracking-tight leading-[1.25]">
                                    Join over <span class="text-indigo-600">10,000+ students</span> <br />
                                    boosting their grades
                                </h2>
                            </div>

                            <!-- Testimonial Quote Box -->
                            <div class="bg-white p-6 rounded-2xl border border-slate-100/70 shadow-sm space-y-4">
                                <p class="text-slate-600 italic text-sm leading-relaxed">
                                    "SkillSwap saved my GPA during finals week. I found a tutor in my dorm building who explained Organic Chemistry in a way my professor never could."
                                </p>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-slate-100 border border-slate-100">
                                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=150" alt="Jessica" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-xs text-slate-800">Jessica M.</h4>
                                        <p class="text-[10px] text-slate-400 font-semibold">Sophomore, Biology Major</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex flex-wrap gap-4 pt-2">
                                <a href="{{ route('register') }}" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-full shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition duration-150 text-sm">
                                    Get Started for Free
                                </a>
                                <a href="{{ route('register') }}" class="px-6 py-3.5 bg-white hover:bg-slate-50 text-slate-700 font-bold rounded-full border border-slate-200/80 shadow-sm hover:shadow hover:-translate-y-0.5 active:translate-y-0 transition duration-150 text-sm">
                                    Become a Tutor
                                </a>
                            </div>
                        </div>

                        <!-- Right Graphic Image Showcase -->
                        <div class="lg:col-span-5 relative flex justify-center">
                            <div class="relative w-full max-w-sm rounded-[24px] overflow-hidden shadow-lg border border-slate-200/50">
                                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80&w=600" alt="Students studying" class="w-full h-80 object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>

                                <!-- Floating Grade Improvement Card -->
                                <div class="absolute bottom-4 left-4 right-4 bg-white/95 backdrop-blur-md p-4 rounded-xl border border-slate-100 flex items-center space-x-3 shadow-md animate-bounce" style="animation-duration: 3s;">
                                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                        📈
                                    </div>
                                    <div>
                                        <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Grade Improved</h5>
                                        <p class="text-xs font-bold text-slate-800">Went from D to A in 3 weeks!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-100 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                    <!-- Left Info -->
                    <div class="md:col-span-6 space-y-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow-sm">
                                <!-- Graduate Hat Icon -->
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2L2 7l10 5 10-5-10-5z" fill="currentColor"/>
                                    <path d="M6 10v6c0 2 2.7 3.5 6 3.5s6-1.5 6-3v-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M21.5 8.5v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <circle cx="20.5" cy="14.5" r="1" fill="currentColor"/>
                                </svg>
                            </div>
                            <span class="font-outfit font-extrabold text-lg text-slate-800 tracking-tight">SkillSwap</span>
                        </div>
                        <p class="text-xs text-slate-500 max-w-sm leading-relaxed">
                            Empowering students to share knowledge and achieve academic success together.
                        </p>
                        <!-- Socials -->
                        <div class="flex items-center space-x-4 pt-2">
                            <a href="#" class="text-slate-400 hover:text-indigo-600 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                            <a href="#" class="text-slate-400 hover:text-indigo-600 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Column 1 -->
                    <div class="md:col-span-3 space-y-4">
                        <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider font-outfit">Learn</h4>
                        <ul class="space-y-2.5 text-xs text-slate-400">
                            <li><a href="{{ route('tutors.index') }}" class="hover:text-indigo-600 transition">Find a Tutor</a></li>
                            <li><a href="{{ route('tutors.index') }}" class="hover:text-indigo-600 transition">Browse Subjects</a></li>
                            <li><a href="#" class="hover:text-indigo-600 transition">Online Tutoring</a></li>
                        </ul>
                    </div>

                    <!-- Column 2 -->
                    <div class="md:col-span-3 space-y-4">
                        <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider font-outfit">Teach</h4>
                        <ul class="space-y-2.5 text-xs text-slate-400">
                            <li><a href="{{ route('register') }}" class="hover:text-indigo-600 transition">Become a Tutor</a></li>
                            <li><a href="#" class="hover:text-indigo-600 transition">Tutor Rules</a></li>
                            <li><a href="#" class="hover:text-indigo-600 transition">Success Tips</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-4">
                    <span>© 2026 SKILLSWAP INC. ALL RIGHTS RESERVED.</span>
                    <div class="flex items-center space-x-6">
                        <a href="#" class="hover:text-indigo-600 transition">PRIVACY</a>
                        <a href="#" class="hover:text-indigo-600 transition">TERMS</a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Typewriter Placeholder Script -->
        <script>
            const words = [
                "Calculus...",
                "Figma auto-layout...",
                "Python loops...",
                "Canva design...",
                "JavaScript APIs...",
                "Resume review...",
                "Physics formulas..."
            ];
            let wordIndex = 0;
            let charIndex = 0;
            let isDeleting = false;
            const searchInput = document.getElementById('search-input');

            function typeEffect() {
                const currentWord = words[wordIndex];
                if (isDeleting) {
                    searchInput.placeholder = "Search for " + currentWord.substring(0, charIndex - 1);
                    charIndex--;
                } else {
                    searchInput.placeholder = "Search for " + currentWord.substring(0, charIndex + 1);
                    charIndex++;
                }

                let speed = 90;
                if (isDeleting) {
                    speed /= 2;
                }

                if (!isDeleting && charIndex === currentWord.length) {
                    isDeleting = true;
                    speed = 1800; // Pause at end of word
                } else if (isDeleting && charIndex === 0) {
                    isDeleting = false;
                    wordIndex = (wordIndex + 1) % words.length;
                    speed = 400; // Pause before typing next word
                }

                setTimeout(typeEffect, speed);
            }

            document.addEventListener("DOMContentLoaded", () => {
                if (searchInput) {
                    typeEffect();
                }

                // Custom Dropdown JS
                const toggleBtn = document.getElementById('dropdown-toggle');
                const menu = document.getElementById('dropdown-menu');
                const arrow = document.getElementById('dropdown-arrow');
                const valueInput = document.getElementById('dropdown-value');
                const label = document.getElementById('dropdown-selected-label');
                const items = document.querySelectorAll('.dropdown-item');

                function toggleDropdown() {
                    const isHidden = menu.classList.contains('hidden');
                    if (isHidden) {
                        menu.classList.remove('hidden');
                        setTimeout(() => {
                            menu.classList.remove('opacity-0', 'scale-95');
                            menu.classList.add('opacity-100', 'scale-100');
                        }, 10);
                        arrow.classList.add('rotate-180');
                    } else {
                        menu.classList.remove('opacity-100', 'scale-100');
                        menu.classList.add('opacity-0', 'scale-95');
                        arrow.classList.remove('rotate-180');
                        setTimeout(() => {
                            menu.classList.add('hidden');
                        }, 200);
                    }
                }

                if (toggleBtn && menu) {
                    toggleBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        toggleDropdown();
                    });

                    items.forEach(item => {
                        item.addEventListener('click', (e) => {
                            e.stopPropagation();
                            const val = item.getAttribute('data-value');
                            const text = item.querySelector('span').innerText;
                            
                            valueInput.value = val;
                            label.innerText = text;
                            
                            items.forEach(i => {
                                const check = i.querySelector('.checkmark');
                                if (i === item) {
                                    check.classList.remove('hidden');
                                    i.classList.add('text-indigo-600');
                                    i.classList.remove('text-slate-600');
                                } else {
                                    check.classList.add('hidden');
                                    i.classList.remove('text-indigo-600');
                                    i.classList.add('text-slate-600');
                                }
                            });
                            
                            toggleDropdown();
                        });
                    });

                    document.addEventListener('click', () => {
                        if (!menu.classList.contains('hidden')) {
                            toggleDropdown();
                        }
                    });
                }
            });
        </script>
    </body>
</html>
