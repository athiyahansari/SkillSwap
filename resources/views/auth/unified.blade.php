@php
    $showLogin = false;
    $showRegister = false;

    if ($errors->any()) {
        if (request()->routeIs('login') || request()->path() === 'login') {
            $showLogin = true;
        } else {
            $showRegister = true;
        }
    } else {
        if ($defaultModal === 'login') {
            $showLogin = true;
        } elseif ($defaultModal === 'register') {
            $showRegister = true;
        }
    }
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="SkillSwap — Connect with peer guides or share your expertise. The modern micro-learning marketplace.">

        <title>SkillSwap - Authentication</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            html {
                font-size: 14.5px;
            }
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .font-outfit {
                font-family: 'Outfit', sans-serif;
            }
            .grid-pattern {
                background-size: 60px 60px;
                background-image:
                    linear-gradient(to right, rgba(99, 102, 241, 0.02) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(99, 102, 241, 0.02) 1px, transparent 1px);
            }
            .hero-glow {
                background: radial-gradient(circle 900px at 50% 50%, rgba(99, 102, 241, 0.05), transparent 80%);
            }
            .modal-enter {
                animation: modalIn 0.25s ease-out;
            }
            @keyframes modalIn {
                from { opacity: 0; transform: scale(0.95) translateY(10px); }
                to { opacity: 1; transform: scale(1) translateY(0); }
            }
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
            .spinner {
                animation: spin 0.7s linear infinite;
            }
        </style>
    </head>
    <body class="bg-[#F8FAFC] text-slate-900 antialiased min-h-screen flex flex-col justify-between grid-pattern relative overflow-hidden">
        <!-- Ambient background glows -->
        <div class="absolute inset-0 hero-glow pointer-events-none"></div>

        <!-- Top logo badge -->
        <div class="pt-12 flex justify-center z-10">
            <a href="/" class="inline-flex items-center space-x-2.5 px-4 py-2 bg-white rounded-full border border-slate-100 shadow-sm hover:shadow transition duration-300 group">
                <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow-sm group-hover:scale-105 transition duration-300">
                    <!-- Graduate Hat Icon -->
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7l10 5 10-5-10-5z" fill="currentColor"/>
                        <path d="M6 10v6c0 2 2.7 3.5 6 3.5s6-1.5 6-3v-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21.5 8.5v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <circle cx="20.5" cy="14.5" r="1" fill="currentColor"/>
                    </svg>
                </div>
                <span class="font-outfit font-extrabold text-xs text-slate-500 uppercase tracking-widest">SkillSwap</span>
            </a>
        </div>

        <!-- Gateway Content -->
        <div class="flex-grow flex flex-col items-center justify-center text-center px-4 max-w-4xl mx-auto z-10 space-y-10 my-16">
            <div class="space-y-4">
                <h1 class="text-5xl sm:text-6xl font-extrabold tracking-tight text-slate-900 font-outfit leading-[1.1]">
                    The Future of <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-amber-500">Peer Learning</span>
                </h1>
                <p class="text-base sm:text-md text-slate-500 max-w-xl mx-auto leading-relaxed">
                    Connect with friendly peer guides or share your knowledge with a global community. Join the most vibrant network of student guides today.
                </p>
            </div>

            <!-- Main Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full max-w-md pt-2">
                <button type="button" onclick="openRegister()" class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 px-8 py-4 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-full shadow-lg shadow-indigo-100 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition duration-150">
                    <span>Register Now</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </button>
                <button type="button" onclick="openLogin()" class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 px-8 py-4 text-sm font-bold text-slate-700 bg-white hover:bg-slate-50 rounded-full border border-slate-200 shadow-sm hover:shadow hover:-translate-y-0.5 active:translate-y-0 transition duration-150">
                    <span>Log In</span>
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </div>

            <p class="text-xs font-semibold text-slate-400">
                New here? Create an account in under a minute!
            </p>
        </div>

        <!-- Footer -->
        <div class="pb-12 flex justify-center text-xs text-slate-400 z-10 font-medium">
            <span>&copy; 2026 SkillSwap Inc. Elevating learning together.</span>
        </div>

        <!-- 1. Log In Modal -->
        <div id="login-modal" class="{{ $showLogin ? '' : 'hidden' }} fixed inset-0 bg-slate-900/30 backdrop-blur-md z-50 flex items-center justify-center p-4" onclick="if(event.target===this) closeAllModals()">
            <div class="modal-enter bg-white/75 backdrop-blur-xl rounded-[32px] w-full max-w-md p-8 border border-white/50 shadow-2xl shadow-indigo-950/15 relative max-h-[90vh] overflow-y-auto">
                <!-- Close Button -->
                <button type="button" onclick="closeAllModals()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 p-1 rounded-full hover:bg-white/50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="space-y-6">
                    <!-- Header -->
                    <div class="space-y-1.5 text-left">
                        <h3 class="text-2xl font-extrabold text-slate-800 font-outfit">Welcome Back</h3>
                        <p class="text-xs text-slate-400 font-medium">Please enter your credentials to continue.</p>
                    </div>

                    <!-- OAuth Error -->
                    @if (session('oauth-error'))
                        <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl text-xs text-rose-600 font-medium">
                            {{ session('oauth-error') }}
                        </div>
                    @endif

                    <!-- Statuses / Validation Errors -->
                    @session('status')
                        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-xs text-emerald-600 font-semibold leading-relaxed">
                            {{ $value }}
                        </div>
                    @endsession

                    @if ($errors->any() && (request()->routeIs('login') || request()->path() === 'login'))
                        <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl text-xs text-rose-600 space-y-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <p>&bull; {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- Google OAuth -->
                    <a href="{{ route('auth.google.redirect') }}" class="w-full inline-flex items-center justify-center space-x-3 px-4 py-3.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm rounded-2xl border border-slate-200 shadow-sm hover:shadow transition duration-150">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        <span>Continue with Google</span>
                    </a>

                    <!-- Divider -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200/60"></div></div>
                        <div class="relative flex justify-center"><span class="bg-white/75 px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">or sign in with email</span></div>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-4" id="login-form">
                        @csrf

                        <!-- Email -->
                        <div class="space-y-1.5 text-left">
                            <label for="login-email" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Email Address</label>
                            <input id="login-email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com" class="w-full px-4 py-3.5 bg-white/50 focus:bg-white text-slate-800 border border-slate-200/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100/50 rounded-2xl text-sm placeholder-slate-400/80 transition duration-200 outline-none">
                        </div>

                        <!-- Password -->
                        <div class="space-y-1.5 text-left relative">
                            <div class="flex items-center justify-between">
                                <label for="login-password" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Password</label>
                            </div>
                            <div class="relative">
                                <input id="login-password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full px-4 py-3.5 pr-10 bg-white/50 focus:bg-white text-slate-800 border border-slate-200/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100/50 rounded-2xl text-sm placeholder-slate-400/80 transition duration-200 outline-none">
                                <button type="button" onclick="togglePasswordVisibility('login-password')" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Checkbox & Link -->
                        <div class="flex items-center justify-between pt-1">
                            <label for="remember_me" class="flex items-center cursor-pointer select-none">
                                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded text-indigo-600 border-slate-200 focus:ring-indigo-500 cursor-pointer">
                                <span class="ms-2 text-xs font-semibold text-slate-500 hover:text-slate-700 transition">Remember Me</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>

                        <!-- Button -->
                        <div class="pt-2">
                            <button type="submit" id="login-btn" class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-100 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition duration-150 flex items-center justify-center space-x-2">
                                <span>Sign In</span>
                                <svg class="w-4 h-4 hidden spinner" id="login-spinner" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            </button>
                        </div>
                    </form>

                    <!-- Switch to Register -->
                    <p class="text-center text-xs text-slate-400 font-medium">
                        Don't have an account?
                        <button type="button" onclick="openRegister()" class="text-indigo-600 font-bold hover:text-indigo-700 transition">Create one</button>
                    </p>
                </div>
            </div>
        </div>

        <!-- 2. Register Modal -->
        <div id="register-modal" class="{{ $showRegister ? '' : 'hidden' }} fixed inset-0 bg-slate-900/30 backdrop-blur-md z-50 flex items-center justify-center p-4" onclick="if(event.target===this) closeAllModals()">
            <div class="modal-enter bg-white/75 backdrop-blur-xl rounded-[32px] w-full max-w-md p-8 border border-white/50 shadow-2xl shadow-indigo-950/15 relative max-h-[90vh] overflow-y-auto">
                <!-- Close Button -->
                <button type="button" onclick="closeAllModals()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 p-1 rounded-full hover:bg-white/50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="space-y-6">
                    <!-- Header -->
                    <div class="space-y-1.5 text-left">
                        <h3 class="text-2xl font-extrabold text-slate-800 font-outfit">Create Account</h3>
                        <p class="text-xs text-slate-400 font-medium">Join the SkillSwap community today.</p>
                    </div>

                    <!-- Validation Errors -->
                    @if ($errors->any() && (request()->routeIs('register') || request()->path() === 'register'))
                        <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl text-xs text-rose-600 space-y-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <p>&bull; {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- Google OAuth -->
                    <a href="{{ route('auth.google.redirect') }}" class="w-full inline-flex items-center justify-center space-x-3 px-4 py-3.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm rounded-2xl border border-slate-200 shadow-sm hover:shadow transition duration-150">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        <span>Continue with Google</span>
                    </a>

                    <!-- Divider -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200/60"></div></div>
                        <div class="relative flex justify-center"><span class="bg-white/75 px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">or register with email</span></div>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('register') }}" class="space-y-4" id="register-form">
                        @csrf

                        <!-- Name -->
                        <div class="space-y-1.5 text-left">
                            <label for="reg-name" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Full Name</label>
                            <input id="reg-name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe" class="w-full px-4 py-3 bg-white/50 focus:bg-white text-slate-800 border border-slate-200/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100/50 rounded-2xl text-sm placeholder-slate-400/80 transition duration-200 outline-none">
                        </div>

                        <!-- Email -->
                        <div class="space-y-1.5 text-left">
                            <label for="reg-email" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Email Address</label>
                            <input id="reg-email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com" class="w-full px-4 py-3 bg-white/50 focus:bg-white text-slate-800 border border-slate-200/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100/50 rounded-2xl text-sm placeholder-slate-400/80 transition duration-200 outline-none">
                        </div>

                        <!-- Password -->
                        <div class="space-y-1.5 text-left relative">
                            <label for="reg-password" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Password</label>
                            <div class="relative">
                                <input id="reg-password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" class="w-full px-4 py-3 pr-10 bg-white/50 focus:bg-white text-slate-800 border border-slate-200/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100/50 rounded-2xl text-sm placeholder-slate-400/80 transition duration-200 outline-none" oninput="updatePasswordStrength(this.value)">
                                <button type="button" onclick="togglePasswordVisibility('reg-password')" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            <!-- Password Strength -->
                            <div id="password-strength" class="hidden mt-1.5">
                                <div class="flex space-x-1">
                                    <div class="h-1 flex-1 rounded-full bg-slate-200 transition-colors duration-200" id="str-1"></div>
                                    <div class="h-1 flex-1 rounded-full bg-slate-200 transition-colors duration-200" id="str-2"></div>
                                    <div class="h-1 flex-1 rounded-full bg-slate-200 transition-colors duration-200" id="str-3"></div>
                                    <div class="h-1 flex-1 rounded-full bg-slate-200 transition-colors duration-200" id="str-4"></div>
                                </div>
                                <p id="str-text" class="text-[10px] font-semibold text-slate-400 mt-1"></p>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-1.5 text-left relative">
                            <label for="reg-password-confirm" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Confirm Password</label>
                            <div class="relative">
                                <input id="reg-password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" class="w-full px-4 py-3 pr-10 bg-white/50 focus:bg-white text-slate-800 border border-slate-200/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100/50 rounded-2xl text-sm placeholder-slate-400/80 transition duration-200 outline-none">
                                <button type="button" onclick="togglePasswordVisibility('reg-password-confirm')" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Role Selector -->
                        <div class="space-y-1.5 text-left">
                            <label for="reg-role" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Register As</label>
                            <select id="reg-role" name="role" required class="w-full px-4 py-3 bg-white/50 focus:bg-white text-slate-700 border border-slate-200/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100/50 rounded-2xl text-sm cursor-pointer transition duration-200 outline-none">
                                <option value="learner" {{ old('role') === 'learner' ? 'selected' : '' }}>Learner (Looking for help)</option>
                                <option value="tutor" {{ old('role') === 'tutor' ? 'selected' : '' }}>Skill Guide (Offering quick help)</option>
                            </select>
                        </div>

                        <!-- Button -->
                        <div class="pt-2">
                            <button type="submit" id="register-btn" class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-100 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition duration-150 flex items-center justify-center space-x-2">
                                <span>Create Account</span>
                                <svg class="w-4 h-4 hidden spinner" id="register-spinner" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            </button>
                        </div>
                    </form>

                    <!-- Switch to Login -->
                    <p class="text-center text-xs text-slate-400 font-medium">
                        Already have an account?
                        <button type="button" onclick="openLogin()" class="text-indigo-600 font-bold hover:text-indigo-700 transition">Sign in</button>
                    </p>
                </div>
            </div>
        </div>

        <!-- Custom JS Script -->
        <script>
            const loginModal = document.getElementById('login-modal');
            const registerModal = document.getElementById('register-modal');

            function openLogin() {
                registerModal.classList.add('hidden');
                loginModal.classList.remove('hidden');
                window.history.pushState({}, '', '{{ route('login') }}');
            }

            function openRegister() {
                loginModal.classList.add('hidden');
                registerModal.classList.remove('hidden');
                window.history.pushState({}, '', '{{ route('register') }}');
            }

            function closeAllModals() {
                loginModal.classList.add('hidden');
                registerModal.classList.add('hidden');
            }

            function togglePasswordVisibility(fieldId) {
                const passwordInput = document.getElementById(fieldId);
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                } else {
                    passwordInput.type = 'password';
                }
            }

            // Password strength indicator
            function updatePasswordStrength(password) {
                const container = document.getElementById('password-strength');
                const bars = [document.getElementById('str-1'), document.getElementById('str-2'), document.getElementById('str-3'), document.getElementById('str-4')];
                const text = document.getElementById('str-text');

                if (password.length === 0) {
                    container.classList.add('hidden');
                    return;
                }
                container.classList.remove('hidden');

                let strength = 0;
                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                if (/\d/.test(password)) strength++;
                if (/[^a-zA-Z\d]/.test(password)) strength++;

                const colors = ['bg-rose-400', 'bg-amber-400', 'bg-sky-400', 'bg-emerald-400'];
                const labels = ['Weak', 'Fair', 'Good', 'Strong'];
                const textColors = ['text-rose-500', 'text-amber-500', 'text-sky-500', 'text-emerald-500'];

                bars.forEach((bar, i) => {
                    bar.className = 'h-1 flex-1 rounded-full transition-colors duration-200 ' + (i < strength ? colors[Math.max(0, strength - 1)] : 'bg-slate-200');
                });

                text.textContent = labels[Math.max(0, strength - 1)] || '';
                text.className = 'text-[10px] font-semibold mt-1 ' + (textColors[Math.max(0, strength - 1)] || 'text-slate-400');
            }

            // Loading spinners
            document.getElementById('login-form').addEventListener('submit', function() {
                document.getElementById('login-btn').disabled = true;
                document.getElementById('login-spinner').classList.remove('hidden');
            });

            document.getElementById('register-form').addEventListener('submit', function() {
                document.getElementById('register-btn').disabled = true;
                document.getElementById('register-spinner').classList.remove('hidden');
            });

            // Close modal when pressing Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeAllModals();
                }
            });
        </script>
    </body>
</html>
