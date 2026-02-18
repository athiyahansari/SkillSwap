<?php
session_start();
if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['role'] ?? '';
    switch ($role) {
        case 'admin':
            header('Location: admin/verifications.php');
            break;
        case 'learner':
            header('Location: learner/dashboard.php');
            break;
        case 'tutor':
            header('Location: tutor/dashboard.php');
            break;
        default:
            // If logged in but role is unknown, do nothing
            break;
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillSwap - Ace Your Exams with Peer Tutors</title>
    <link href="../style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        html { font-size: 11px !important; }
        body { font-family: 'Inter', sans-serif; font-size: 1rem !important; line-height: 1.6 !important; }
        .gradient-text {
            background: linear-gradient(to right, #8B5CF6, #EC4899, #F59E0B);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Reveal Animation */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 overflow-x-hidden">
    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 glass px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <a href="/index.php" class="flex items-center gap-2">
                <span class="font-bold text-xl">SkillSwap</span>
            </a>

        </div>
        <div class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
            <a href="auth.php?from=search" class="hover:text-indigo-600 transition">Find Tutors</a>
            <a href="auth.php" class="hover:text-indigo-600 transition">Become a Tutor</a>
        </div>
        <div class="flex items-center gap-4">
            <a href="auth.php" class="text-sm font-medium hover:text-indigo-600 transition">Log In</a>
            <a href="auth.php" class="bg-indigo-600 text-white px-5 py-2.5 rounded-full text-sm font-medium hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Sign Up</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 px-6 max-w-7xl mx-auto text-center overflow-visible">
        <!-- Background Blobs -->
        <div class="absolute top-20 left-10 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-20 right-10 w-72 h-72 bg-yellow-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 bg-white px-4 py-1.5 rounded-full shadow-sm border border-slate-100 mb-8">
                <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                <span class="text-xs font-semibold text-slate-500 tracking-wider">NEW SEMESTER READY</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-8">
                Ace Your Exams with<br>
                <span class="gradient-text">Peer Tutors</span>
            </h1>

            <!-- Search Bar -->
            <div class="max-w-3xl mx-auto bg-white rounded-full p-2 shadow-2xl shadow-indigo-100 flex flex-col md:flex-row items-center gap-2 border border-slate-100 reveal">
                <div class="flex-1 flex items-center gap-3 px-4 w-full">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" id="typing-input" class="w-full bg-transparent border-none focus:ring-0 text-slate-700 placeholder:text-slate-400" placeholder="">
                </div>
                <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
                
                <!-- Fancy Dropdown -->
                <div class="relative w-full md:w-auto">
                    <button id="dropdown-btn" class="flex items-center gap-3 px-6 py-2.5 rounded-full hover:bg-slate-50 transition text-slate-600 text-sm font-medium w-full md:w-44 justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span id="selected-university" class="truncate">University</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-300" id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div id="dropdown-menu" class="absolute top-full left-0 right-0 mt-3 bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 py-2 hidden z-[100] max-h-60 overflow-y-auto overflow-x-hidden animate-in fade-in slide-in-from-top-2 duration-300">
                        <div class="px-3 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Select School</div>
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-indigo-50 hover:text-indigo-600 transition text-slate-600 university-option flex items-center gap-2">
                             <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div> MIT
                        </button>
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-indigo-50 hover:text-indigo-600 transition text-slate-600 university-option flex items-center gap-2">
                             <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div> Harvard
                        </button>
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-indigo-50 hover:text-indigo-600 transition text-slate-600 university-option flex items-center gap-2">
                             <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div> Stanford
                        </button>
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-indigo-50 hover:text-indigo-600 transition text-slate-600 university-option flex items-center gap-2">
                             <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div> Berkeley
                        </button>
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-indigo-50 hover:text-indigo-600 transition text-slate-600 university-option flex items-center gap-2">
                             <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div> Yale
                        </button>
                    </div>
                </div>

                <a href="auth.php?from=search" class="bg-indigo-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-indigo-700 transition w-full md:w-auto">Find Tutors</a>
            </div>
        </div>
    </section>

    <!-- Why Choose Section -->
    <section class="py-20 px-6 max-w-7xl mx-auto text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4 reveal">Why Choose SkillSwap?</h2>
        <p class="text-slate-500 mb-16 reveal">We make it safe and easy to find the perfect peer tutor.</p>
        
        <div class="grid md:grid-cols-3 gap-8 text-left">
            <div class="bg-white p-8 rounded-3xl border border-slate-100 hover:shadow-xl transition duration-500 group reveal">
                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-6 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Verified Knowledge</h3>
                <p class="text-slate-500 leading-relaxed">Every tutor is vetted for academic excellence. We check transcripts so you don't have to.</p>
            </div>
            
            <div class="bg-white p-8 rounded-3xl border border-slate-100 hover:shadow-xl transition duration-500 group reveal delay-100">
                <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Flexible Scheduling</h3>
                <p class="text-slate-500 leading-relaxed">Book sessions that fit your busy student life. Late night study session? No problem.</p>
            </div>
            
            <div class="bg-white p-8 rounded-3xl border border-slate-100 hover:shadow-xl transition duration-500 group reveal delay-200">
                <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 mb-6 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Affordable Rates</h3>
                <p class="text-slate-500 leading-relaxed">Quality tutoring that doesn't break the bank. Peer rates are often 50% less than pros.</p>
            </div>
        </div>
    </section>

    <!-- Top Rated Tutors -->
    <section class="py-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 mb-12 flex items-end justify-between reveal">
            <div>
                <h2 class="text-3xl font-bold mb-2">Top Rated Tutors</h2>
                <p class="text-slate-500">Find the best help in your area</p>
            </div>
            <a href="#" class="text-indigo-600 font-semibold flex items-center gap-2 hover:gap-3 transition-all">
                View all tutors
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
        
        <div class="flex gap-6 overflow-x-auto px-6 md:px-12 lg:px-24 hide-scrollbar pb-10 reveal">
            <!-- Tutor Card 1 -->
            <div class="min-w-[300px] bg-white rounded-3xl border border-slate-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition duration-500 group">
                <div class="relative h-64 overflow-hidden">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Sarah" alt="Sarah" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full flex items-center gap-1 shadow-sm">
                        <span class="text-yellow-400 text-sm">★</span>
                        <span class="text-xs font-bold text-slate-800">5.0</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-bold">Sarah Jenkins</h3>
                        <span class="text-indigo-600 font-bold">$20<span class="text-slate-400 font-normal text-xs">/hr</span></span>
                    </div>
                    <p class="text-slate-500 text-sm mb-4">Calculus & Physics</p>
                    <div class="flex gap-2">
                        <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">MIT</span>
                        <span class="bg-indigo-50 text-indigo-600 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Verified</span>
                    </div>
                    <button class="w-full mt-6 bg-slate-50 text-indigo-600 font-bold py-3 rounded-2xl group-hover:bg-indigo-600 group-hover:text-white transition duration-300">View Profile</button>
                </div>
            </div>

            <!-- Tutor Card 2 -->
            <div class="min-w-[300px] bg-white rounded-3xl border border-slate-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition duration-500 group">
                <div class="relative h-64 overflow-hidden">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Mike" alt="Mike" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full flex items-center gap-1 shadow-sm">
                        <span class="text-yellow-400 text-sm">★</span>
                        <span class="text-xs font-bold text-slate-800">4.9</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-bold">Mike Thompson</h3>
                        <span class="text-indigo-600 font-bold">$18<span class="text-slate-400 font-normal text-xs">/hr</span></span>
                    </div>
                    <p class="text-slate-500 text-sm mb-4">English Literature</p>
                    <div class="flex gap-2">
                        <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">NYU</span>
                        <span class="bg-indigo-50 text-indigo-600 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Verified</span>
                    </div>
                    <button class="w-full mt-6 bg-slate-50 text-indigo-600 font-bold py-3 rounded-2xl group-hover:bg-indigo-600 group-hover:text-white transition duration-300">View Profile</button>
                </div>
            </div>

            <!-- Tutor Card 3 -->
            <div class="min-w-[300px] bg-white rounded-3xl border border-slate-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition duration-500 group">
                <div class="relative h-64 overflow-hidden">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Emily" alt="Emily" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full flex items-center gap-1 shadow-sm">
                        <span class="text-yellow-400 text-sm">★</span>
                        <span class="text-xs font-bold text-slate-800">5.0</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-bold">Emily Rodriguez</h3>
                        <span class="text-indigo-600 font-bold">$25<span class="text-slate-400 font-normal text-xs">/hr</span></span>
                    </div>
                    <p class="text-slate-500 text-sm mb-4">Biology & Chem</p>
                    <div class="flex gap-2">
                        <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Stanford</span>
                        <span class="bg-indigo-50 text-indigo-600 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Verified</span>
                    </div>
                    <button class="w-full mt-6 bg-slate-50 text-indigo-600 font-bold py-3 rounded-2xl group-hover:bg-indigo-600 group-hover:text-white transition duration-300">View Profile</button>
                </div>
            </div>

            <!-- Tutor Card 4 -->
            <div class="min-w-[300px] bg-white rounded-3xl border border-slate-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition duration-500 group">
                <div class="relative h-64 overflow-hidden">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=David" alt="David" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full flex items-center gap-1 shadow-sm">
                        <span class="text-yellow-400 text-sm">★</span>
                        <span class="text-xs font-bold text-slate-800">4.8</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-bold">David Kim</h3>
                        <span class="text-indigo-600 font-bold">$22<span class="text-slate-400 font-normal text-xs">/hr</span></span>
                    </div>
                    <p class="text-slate-500 text-sm mb-4">Computer Science</p>
                    <div class="flex gap-2">
                        <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Berkeley</span>
                        <span class="bg-indigo-50 text-indigo-600 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Verified</span>
                    </div>
                    <button class="w-full mt-6 bg-slate-50 text-indigo-600 font-bold py-3 rounded-2xl group-hover:bg-indigo-600 group-hover:text-white transition duration-300">View Profile</button>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-6">
        <div class="max-w-7xl mx-auto bg-slate-100 rounded-[40px] p-8 md:p-16 flex flex-col md:flex-row items-center gap-12 overflow-hidden relative reveal">
             <!-- Background Shape -->
             <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>

            <div class="flex-1 text-center md:text-left">
                <h2 class="text-4xl md:text-5xl font-bold mb-6">
                    Join over <span class="text-indigo-600">10,000+ students</span> boosting their grades
                </h2>
                <div class="mb-10 p-6 bg-white rounded-2xl shadow-sm border border-slate-50 relative">
                    <p class="text-slate-600 italic mb-6 leading-relaxed">
                        "SkillSwap saved my GPA during finals week. I found a tutor in my dorm building who explained Organic Chemistry in a way my professor never could."
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&q=80&w=100" alt="Jessica" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-sm font-bold">Jessica M.</p>
                            <p class="text-[11px] text-slate-400">Sophomore, Biology Major</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <a href="auth.php" class="bg-indigo-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-indigo-700 transition shadow-xl shadow-indigo-100 text-center w-full sm:w-auto">Get Started for Free</a>
                    <a href="#" class="bg-white border border-slate-200 text-slate-600 px-8 py-4 rounded-2xl font-bold hover:bg-slate-50 transition text-center w-full sm:w-auto">Become a Tutor</a>
                </div>
            </div>
            
            <div class="flex-1 relative">
                <div class="bg-purple-100 rounded-3xl p-4 transform rotate-2">
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80&w=800" alt="Students studying" class="rounded-2xl shadow-lg -rotate-2">
                </div>
                <!-- Mini Stats Card -->
                <div class="absolute -left-6 bottom-10 bg-white p-4 rounded-2xl shadow-xl flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider leading-none mb-1">Grade Improved</p>
                        <p class="text-sm font-bold text-slate-800">Went from D to A in 3 weeks!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white pt-20 pb-10 px-6 border-t border-slate-100">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-16 mb-20">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span class="text-xl font-bold uppercase tracking-tight">SkillSwap</span>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed max-w-xs mb-8">
                    Empowering students to share knowledge and achieve academic success together.
                </p>
                <div class="flex items-center gap-4 text-slate-400">
                    <a href="https://x.com" class="hover:text-indigo-600"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                    <a href="https://instagram.com" class="hover:text-indigo-600"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-12 flex-[2]">
                <div>
                    <h4 class="font-bold mb-6">Learn</h4>
                    <ul class="text-sm text-slate-500 space-y-4">
                        <li><a href="auth.php" class="hover:text-indigo-600 transition">Find a Tutor</a></li>
                        <li><a href="auth.php" class="hover:text-indigo-600 transition">Browse Subjects</a></li>
                        <li><a href="auth.php" class="hover:text-indigo-600 transition">Online Tutoring</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6">Teach</h4>
                    <ul class="text-sm text-slate-500 space-y-4">
                        <li><a href="auth.php" class="hover:text-indigo-600 transition">Become a Tutor</a></li>
                        <li><a href="auth.php" class="hover:text-indigo-600 transition">Tutor Rules</a></li>
                        <li><a href="auth.php" class="hover:text-indigo-600 transition">Success Tips</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
            <p>© 2026 SKILLSWAP INC. ALL RIGHTS RESERVED.</p>
            <div class="flex gap-8">
                <a href="#" class="hover:text-slate-600">PRIVACY</a>
                <a href="#" class="hover:text-slate-600">TERMS</a>
            </div>
        </div>
    </footer>

    <script src="../resources/js/typing-effect.js"></script>
    <script src="../resources/js/ui-interactions.js"></script>
</body>
</html>
