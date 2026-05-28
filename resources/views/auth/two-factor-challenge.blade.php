<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Two-Factor Verification - SkillSwap</title>

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
            [x-cloak] { display: none !important; }
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
        <div class="flex-grow flex items-center justify-center px-4 z-10 my-8" x-data="{ recovery: false, loading: false }">
            <div class="bg-white/75 backdrop-blur-xl rounded-[32px] w-full max-w-md p-8 border border-white/50 shadow-2xl shadow-indigo-950/15">
                <div class="space-y-6">
                    <!-- Header -->
                    <div class="space-y-3 text-left">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center">
                            <!-- Shield Lock Icon -->
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-extrabold text-slate-800 font-['Outfit']">Two-Factor Verification</h3>
                            <!-- Auth code mode description -->
                            <p class="text-xs text-slate-400 font-medium mt-1.5" x-show="!recovery">
                                Enter the authentication code from your authenticator app to continue.
                            </p>
                            <!-- Recovery mode description -->
                            <p class="text-xs text-slate-400 font-medium mt-1.5" x-cloak x-show="recovery">
                                Lost your authenticator? Enter one of your emergency recovery codes.
                            </p>
                        </div>
                    </div>

                    <!-- Validation Errors (Fortify component) -->
                    <x-validation-errors class="p-4 bg-rose-50 border border-rose-100 rounded-2xl text-xs text-rose-600 font-medium" />

                    <!-- Form -->
                    <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4" @submit="loading = true">
                        @csrf

                        <!-- Authentication Code Input -->
                        <div class="space-y-1.5 text-left" x-show="!recovery">
                            <label for="code" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Authentication Code</label>
                            <input id="code" type="text" inputmode="numeric" name="code" autofocus x-ref="code" autocomplete="one-time-code" placeholder="000000" class="w-full px-4 py-3.5 bg-white/50 focus:bg-white text-slate-800 border border-slate-200/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100/50 rounded-2xl text-sm placeholder-slate-400/80 transition duration-200 outline-none tracking-[0.3em] text-center font-semibold">
                        </div>

                        <!-- Recovery Code Input -->
                        <div class="space-y-1.5 text-left" x-cloak x-show="recovery">
                            <label for="recovery_code" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Recovery Code</label>
                            <input id="recovery_code" type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" placeholder="xxxxx-xxxxx" class="w-full px-4 py-3.5 bg-white/50 focus:bg-white text-slate-800 border border-slate-200/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100/50 rounded-2xl text-sm placeholder-slate-400/80 transition duration-200 outline-none tracking-wider text-center font-semibold">
                        </div>

                        <!-- Toggle Button -->
                        <div class="text-center">
                            <button type="button"
                                x-show="!recovery"
                                @click="recovery = true; $nextTick(() => { $refs.recovery_code.focus() })"
                                class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition">
                                Use a recovery code instead
                            </button>
                            <button type="button"
                                x-cloak
                                x-show="recovery"
                                @click="recovery = false; $nextTick(() => { $refs.code.focus() })"
                                class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition">
                                Use an authentication code instead
                            </button>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" :disabled="loading" class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-100 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition duration-150 disabled:opacity-70 disabled:cursor-not-allowed disabled:hover:translate-y-0 inline-flex items-center justify-center space-x-2">
                                <!-- Spinner -->
                                <svg x-show="loading" x-cloak class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="loading ? 'Verifying...' : 'Verify & Log In'"></span>
                            </button>
                        </div>
                    </form>

                    <!-- Back to login -->
                    <div class="text-center pt-2">
                        <a href="{{ route('login') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-700 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Back to login</span>
                        </a>
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
