<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Verify Email - SkillSwap</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            html { font-size: 14.5px; }
            .grid-pattern { background-size: 60px 60px; background-image: linear-gradient(to right, rgba(99, 102, 241, 0.02) 1px, transparent 1px), linear-gradient(to bottom, rgba(99, 102, 241, 0.02) 1px, transparent 1px); }
            .hero-glow { background: radial-gradient(circle 900px at 50% 50%, rgba(99, 102, 241, 0.05), transparent 80%); }
        </style>
    </head>
    <body class="bg-[#F8FAFC] text-slate-900 font-['Plus_Jakarta_Sans'] antialiased min-h-screen flex flex-col justify-between grid-pattern relative overflow-hidden">
        <!-- Ambient background glow -->
        <div class="absolute inset-0 hero-glow pointer-events-none"></div>

        <!-- Top logo badge -->
        <div class="pt-12 flex justify-center z-10">
            <a href="/" class="inline-flex items-center space-x-2.5 px-4 py-2 bg-white rounded-full border border-slate-100 shadow-sm hover:shadow transition duration-300 group">
                <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow-sm group-hover:scale-105 transition duration-300">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7l10 5 10-5-10-5z" fill="currentColor"/>
                        <path d="M6 10v6c0 2 2.7 3.5 6 3.5s6-1.5 6-3v-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21.5 8.5v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <circle cx="20.5" cy="14.5" r="1" fill="currentColor"/>
                    </svg>
                </div>
                <span class="font-['Outfit'] font-extrabold text-xs text-slate-500 uppercase tracking-widest">SkillSwap</span>
            </a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow flex items-center justify-center px-4 z-10 my-8" x-data="{ loading: false }">
            <div class="bg-white/75 backdrop-blur-xl rounded-[32px] w-full max-w-md p-8 border border-white/50 shadow-2xl shadow-indigo-950/15">
                <div class="space-y-6">
                    <!-- Header -->
                    <div class="space-y-3 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center mx-auto">
                            <!-- Envelope Icon -->
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-extrabold text-slate-800 font-['Outfit']">Check Your Email</h3>
                            <p class="text-xs text-slate-400 font-medium mt-1.5 leading-relaxed max-w-xs mx-auto">Almost there! We've sent a verification link to your email address. Click the link to activate your SkillSwap account.</p>
                        </div>
                    </div>

                    <!-- User Email Badge -->
                    <div class="flex justify-center">
                        <div class="inline-flex items-center space-x-2 px-4 py-2.5 bg-indigo-50/80 border border-indigo-100 rounded-2xl">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                            <span class="text-sm font-semibold text-indigo-700">{{ auth()->user()->email }}</span>
                        </div>
                    </div>

                    <!-- Success Status -->
                    @if (session('status') == 'verification-link-sent')
                        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-xs text-emerald-600 font-semibold leading-relaxed text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>A new verification link has been sent to your email address.</span>
                            </div>
                        </div>
                    @endif

                    <!-- Resend Form -->
                    <form method="POST" action="{{ route('verification.send') }}" @submit="loading = true">
                        @csrf

                        <button type="submit" :disabled="loading" class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-100 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition duration-150 disabled:opacity-70 disabled:cursor-not-allowed disabled:hover:translate-y-0 inline-flex items-center justify-center space-x-2">
                            <!-- Spinner -->
                            <svg x-show="loading" x-cloak class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="loading ? 'Sending...' : 'Resend Verification Email'"></span>
                        </button>
                    </form>

                    <!-- Bottom Links -->
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('profile.show') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-700 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Edit Profile</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-400 hover:text-slate-600 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="pb-12 flex justify-center text-xs text-slate-400 z-10 font-medium">
            <span>&copy; {{ date('Y') }} SkillSwap Inc. Elevating learning together.</span>
        </div>
    </body>
</html>
