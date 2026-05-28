<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Create New Password - SkillSwap</title>

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
        <div class="flex-grow flex items-center justify-center px-4 z-10 my-8" x-data="{ loading: false, showPassword: false, showConfirm: false }">
            <div class="bg-white/75 backdrop-blur-xl rounded-[32px] w-full max-w-md p-8 border border-white/50 shadow-2xl shadow-indigo-950/15">
                <div class="space-y-6">
                    <!-- Header -->
                    <div class="space-y-3 text-left">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center">
                            <!-- Lock Icon -->
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-extrabold text-slate-800 font-['Outfit']">Create New Password</h3>
                            <p class="text-xs text-slate-400 font-medium mt-1.5">Choose a strong password to keep your account secure.</p>
                        </div>
                    </div>

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl text-xs text-rose-600 space-y-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <p>• {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- Form -->
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-4" @submit="loading = true">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email -->
                        <div class="space-y-1.5 text-left">
                            <label for="email" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Email Address</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" placeholder="you@example.com" class="w-full px-4 py-3.5 bg-white/50 focus:bg-white text-slate-800 border border-slate-200/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100/50 rounded-2xl text-sm placeholder-slate-400/80 transition duration-200 outline-none">
                        </div>

                        <!-- Password -->
                        <div class="space-y-1.5 text-left">
                            <label for="password" class="text-xs font-bold text-slate-500 uppercase tracking-wider">New Password</label>
                            <div class="relative">
                                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="••••••••" class="w-full px-4 py-3.5 pr-10 bg-white/50 focus:bg-white text-slate-800 border border-slate-200/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100/50 rounded-2xl text-sm placeholder-slate-400/80 transition duration-200 outline-none">
                                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 transition">
                                    <!-- Eye Icon -->
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <!-- Eye Off Icon -->
                                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-1.5 text-left">
                            <label for="password_confirmation" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Confirm Password</label>
                            <div class="relative">
                                <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" class="w-full px-4 py-3.5 pr-10 bg-white/50 focus:bg-white text-slate-800 border border-slate-200/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100/50 rounded-2xl text-sm placeholder-slate-400/80 transition duration-200 outline-none">
                                <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 transition">
                                    <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showConfirm" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" :disabled="loading" class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-100 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition duration-150 disabled:opacity-70 disabled:cursor-not-allowed disabled:hover:translate-y-0 inline-flex items-center justify-center space-x-2">
                                <!-- Spinner -->
                                <svg x-show="loading" x-cloak class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="loading ? 'Resetting Password...' : 'Reset Password'"></span>
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
