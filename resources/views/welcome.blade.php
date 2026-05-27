<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SkillSwap - Peer-to-Peer Micro-Learning Marketplace</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif

        <style>
            body {
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            }
        </style>
    </head>
    <body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white border-b border-slate-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center space-x-2 text-indigo-600 font-extrabold text-xl tracking-tight">
                    <span>SkillSwap</span>
                </a>
                <nav class="hidden md:flex space-x-8 text-sm font-semibold text-slate-600">
                    <a href="{{ route('tutors.index') }}" class="hover:text-indigo-600 transition">Find Skill Guides</a>
                </nav>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ auth()->user()->dashboardUrl() }}" class="text-sm font-bold text-slate-700 hover:text-indigo-600 transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition">Log in</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">Sign up</a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
            <!-- Hero Section -->
            <section class="relative py-20 lg:py-28 overflow-hidden bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                        <div class="lg:col-span-7 space-y-6 text-left">
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                🚀 Peer-to-Peer Micro-Learning
                            </div>
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-tight">
                                Swap Skills. Learn Fast. <span class="text-indigo-600">Build Together.</span>
                            </h1>
                            <p class="text-lg text-slate-500 max-w-xl leading-relaxed">
                                Forget academic lectures. Connect with friendly peers for quick, session-based help. Solve a calculus problem, learn Canva & Figma basics, or debug Python code in real-time.
                            </p>
                            <div class="pt-4 flex flex-wrap gap-4">
                                <a href="{{ route('tutors.index') }}" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-150">
                                    Find a Skill Guide
                                </a>
                                <a href="{{ route('register') }}" class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl hover:-translate-y-0.5 transition-all duration-150">
                                    Become a Guide
                                </a>
                            </div>
                        </div>
                        <div class="lg:col-span-5 relative">
                            <!-- Visual Graphic Card Showcase -->
                            <div class="bg-gradient-to-tr from-indigo-50 to-purple-50 rounded-3xl p-8 border border-slate-100 shadow-sm relative overflow-hidden">
                                <div class="space-y-4">
                                    <!-- Card 1 -->
                                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100/50 flex items-start space-x-3 transform -rotate-1 hover:rotate-0 transition duration-300">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">JS</div>
                                        <div>
                                            <h4 class="font-bold text-sm text-slate-800">JavaScript Bug Debugging</h4>
                                            <p class="text-xs text-slate-500 mt-0.5">"Help me fetch this API correctly."</p>
                                            <span class="inline-block mt-2 text-[10px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">30-min session</span>
                                        </div>
                                    </div>
                                    <!-- Card 2 -->
                                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100/50 flex items-start space-x-3 transform rotate-1 hover:rotate-0 transition duration-300">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">FI</div>
                                        <div>
                                            <h4 class="font-bold text-sm text-slate-800">Figma Auto-Layout Help</h4>
                                            <p class="text-xs text-slate-500 mt-0.5">"Why does my card layout break on resize?"</p>
                                            <span class="inline-block mt-2 text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">1 hour session</span>
                                        </div>
                                    </div>
                                    <!-- Card 3 -->
                                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100/50 flex items-start space-x-3 transform -rotate-2 hover:rotate-0 transition duration-300">
                                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">MA</div>
                                        <div>
                                            <h4 class="font-bold text-sm text-slate-800">Tricky Calculus Limits</h4>
                                            <p class="text-xs text-slate-500 mt-0.5">"Solve this exam limit question step-by-step."</p>
                                            <span class="inline-block mt-2 text-[10px] font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">45-min session</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features / Examples Grid -->
            <section class="py-16 bg-slate-50 border-t border-slate-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto space-y-3">
                        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">How it works</h2>
                        <p class="text-slate-500">SkillSwap is built for rapid, flexible knowledge exchange. Real people, helpful guidance, no academic red tape.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                        <!-- Box 1 -->
                        <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm space-y-4 hover:shadow-md transition">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <h3 class="font-extrabold text-lg text-slate-800">1. Explore Practical Skills</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">
                                Browse categories like Figma, Python, Canva Design, or school algebra. Find guides who explain things simply.
                            </p>
                        </div>
                        <!-- Box 2 -->
                        <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm space-y-4 hover:shadow-md transition">
                            <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="font-extrabold text-lg text-slate-800">2. Book a Quick Session</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">
                                Pick a date, input your specific focus (like an assignment block or a tool feature), and schedule an instant session.
                            </p>
                        </div>
                        <!-- Box 3 -->
                        <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm space-y-4 hover:shadow-md transition">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <h3 class="font-extrabold text-lg text-slate-800">3. Learn Peer-to-Peer</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">
                                Share screens, tackle problems together, and exchange tips on a friendly, casual video call.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Popular Skills Section -->
            <section class="py-16 bg-white border-t border-slate-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                        <div class="space-y-2">
                            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Popular Topics & Skills</h2>
                            <p class="text-slate-500">Quick help is available across these trending skills and school revision topics.</p>
                        </div>
                        <a href="{{ route('tutors.index') }}" class="text-indigo-600 hover:text-indigo-700 font-bold text-sm inline-flex items-center">
                            Browse All Skills
                            <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @php
                            $popular = [
                                'Canva Design' => '🎨 Post templates & decks',
                                'Figma Basics' => '📐 Layouts & prototypes',
                                'Python Programming' => '🐍 Debugging & scripts',
                                'JavaScript Basics' => '🌐 DOM & API requests',
                                'Resume Review' => '📄 Layout & wording tips',
                                'UI/UX Design' => '✨ Wireframes & wireflows',
                                'Mathematics' => '📐 Calculus & algebra help',
                                'Video Editing' => '🎬 Trimming & transitions',
                            ];
                        @endphp
                        @foreach ($popular as $name => $desc)
                            <a href="{{ route('tutors.index') }}" class="p-6 rounded-2xl border border-slate-100 hover:border-indigo-100 hover:bg-indigo-50/10 transition text-left group">
                                <h4 class="font-extrabold text-slate-800 text-sm group-hover:text-indigo-600 transition">{{ $name }}</h4>
                                <p class="text-xs text-slate-400 mt-1">{{ $desc }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-100 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <span class="text-sm font-bold text-indigo-600">SkillSwap</span>
                <p class="text-xs text-slate-400">© 2026 SkillSwap. Approachable peer-to-peer knowledge sharing.</p>
            </div>
        </footer>
    </body>
</html>
