<?php
session_start();
require_once '../functions/authFunctions.php';
require_once '../functions/flash.php';

$error = $_GET['error'] ?? null;
$from = $_GET['from'] ?? null;

if ($from === 'search') {
    setFlash('info', 'Please log in to search for tutors.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to SkillSwap - Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        html { font-size: 11px !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem !important; line-height: 1.6 !important; }
        .bg-soft-gradient {
            background: radial-gradient(circle at top right, #f8faff 0%, #ffffff 40%, #fffbf8 100%),
                        linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%);
        }
        .gradient-text {
            background: linear-gradient(to right, #8B5CF6, #EC4899, #F59E0B);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glass-modal {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body class="bg-soft-gradient min-h-screen flex flex-col items-center justify-center p-6 relative overflow-x-hidden selection:bg-indigo-100 selection:text-indigo-900">

    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-100/40 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-orange-100/30 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <!-- Hero Section -->
    <header class="w-full max-w-4xl flex flex-col items-center text-center">
        <!-- Brand Label -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/50 backdrop-blur-md border border-white/20 shadow-sm mb-12 animate-in fade-in slide-in-from-top-4 duration-700">
            <div class="w-6 h-6 bg-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-100">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <span class="text-sm font-bold text-slate-600 tracking-wide uppercase">SkillSwap</span>
        </div>

        <!-- Headline -->
        <h1 class="text-5xl md:text-7xl font-[900] text-slate-900 leading-[1.1] mb-8 animate-in fade-in slide-in-from-top-6 duration-1000">
            The Future of <br>
            <span class="gradient-text">Peer Learning</span>
        </h1>

        <!-- Supporting Paragraph -->
        <p class="text-slate-500 text-lg md:text-xl font-medium leading-relaxed max-w-2xl mb-16 animate-in fade-in slide-in-from-top-8 duration-1000 delay-200">
            Connect with expert tutors or share your knowledge with a global community. <br class="hidden md:block"> Join the most vibrant network of student experts today.
        </p>

        <!-- Flash Notifications -->
        <?php $flash = getFlash(); ?>
        <?php if ($flash): ?>
        <div class="w-full max-w-md mb-8 p-6 rounded-[2rem] border animate-in slide-in-from-top-4 duration-300 flex items-center gap-4 shadow-sm
            <?= $flash['type'] === 'success' ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : '' ?>
            <?= $flash['type'] === 'error' ? 'bg-red-50 border-red-100 text-red-700' : '' ?>
            <?= $flash['type'] === 'info' ? 'bg-blue-50 border-blue-100 text-blue-700' : '' ?>
        ">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center
                <?= $flash['type'] === 'success' ? 'bg-emerald-100' : '' ?>
                <?= $flash['type'] === 'error' ? 'bg-red-100' : '' ?>
                <?= $flash['type'] === 'info' ? 'bg-blue-100' : '' ?>
            ">
                <?php if ($flash['type'] === 'success'): ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                <?php elseif ($flash['type'] === 'error'): ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                <?php else: ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <?php endif; ?>
            </div>
            <span class="font-bold"><?= htmlspecialchars($flash['message']) ?></span>
        </div>
        <?php endif; ?>
    </header>

    <!-- CTA Section Card -->
    <main class="w-full max-w-3xl bg-white/40 backdrop-blur-sm border border-white/60 p-3 rounded-[3rem] shadow-2xl shadow-slate-200/50 my-12 animate-in fade-in slide-in-from-bottom-8 duration-1000 delay-300">
        <div class="flex flex-col md:flex-row gap-4">
            <button onclick="openRegModal()" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-6 px-10 rounded-[2rem] border-2 border-white/20 flex items-center justify-center gap-4 shadow-xl shadow-indigo-100/50 transition-all duration-300 hover:-translate-y-1 active:scale-95 group focus-visible:ring-4 focus-visible:ring-indigo-200 outline-none">
                <span class="text-xl">Register Now</span>
                <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </button>

            <button onclick="openModal()" class="flex-1 bg-white hover:bg-slate-50 text-slate-900 border-2 border-indigo-600/20 font-bold py-6 px-10 rounded-[2rem] flex items-center justify-center gap-4 transition-all duration-300 hover:-translate-y-1 active:scale-95 group focus-visible:ring-4 focus-visible:ring-slate-200 outline-none">
                <span class="text-xl">Log in</span>
                <svg class="w-6 h-6 text-indigo-600 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </button>
        </div>
    </main>


    <!-- Footer Links -->
    <footer class="w-full text-center pb-8">
        <p class="text-slate-500 font-medium mb-8">
            New here? Create an account in under a minute!
        </p>
        
        <p class="text-slate-400 text-sm font-medium">© 2026 SkillSwap Inc. Elevating learning together.</p>
    </footer>

    <!-- Glassmorphism Login Modal -->
    <div id="loginModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden animate-in fade-in duration-300">
        <div class="glass-modal w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden">
            <!-- Background Accent -->
            <div id="modalAccent" class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-400/20 rounded-full blur-3xl"></div>
            
            <button onclick="closeModal('loginModal')" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="text-center mb-10">
                <h3 id="modalTitle" class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Welcome Back</h3>
                <p class="text-slate-500 font-medium">Please enter your credentials to continue.</p>
            </div>

            <form action="../api/login.php" method="POST" class="space-y-6" id="loginForm">
                <?php echo csrf_field(); ?>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email Address</label>
                    <input type="email" name="email" id="emailInput" required placeholder="name@university.edu" 
                           class="w-full px-6 py-4 rounded-2xl border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-indigo-600 bg-white shadow-sm transition-all outline-none placeholder:text-slate-300">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="loginPass" required placeholder="••••••••" 
                               class="w-full px-6 py-4 rounded-2xl border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-indigo-600 bg-white shadow-sm transition-all outline-none placeholder:text-slate-300">
                        <button type="button" onclick="togglePassword('loginPass')" class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between ml-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="rememberMe" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        <span class="text-[13px] font-bold text-slate-500">Remember Me</span>
                    </label>
                    <a href="auth/forgot-password.php" class="text-[13px] font-bold text-indigo-600 hover:underline">Forgot Password?</a>
                </div>

                <button type="submit" id="submitBtn" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-5 rounded-2xl shadow-xl shadow-indigo-100 transition-all active:scale-95 mt-4">
                    Sign In
                </button>
            </form>
        </div>
    </div>

    <!-- Glassmorphism Registration Selection Modal -->
    <div id="regModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden animate-in fade-in duration-300">
        <div class="glass-modal w-full max-w-lg rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden text-center">
            <div class="absolute -top-24 -left-24 w-48 h-48 bg-purple-400/20 rounded-full blur-3xl"></div>
            
            <button onclick="closeModal('regModal')" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <h3 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Join SkillSwap</h3>
            <p class="text-slate-500 font-medium mb-10">Select how you want to get started with us.</p>

            <div class="grid gap-6">
                <!-- Register as Learner -->
                <a href="learner/register.php" class="group bg-white/40 hover:bg-white/60 backdrop-blur-sm p-6 rounded-[1.5rem] border border-white/50 flex items-center gap-6 text-left transition-all hover:scale-[1.03] active:scale-95">
                    <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:scale-110 transition">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-lg">I'm a Learner</h4>
                        <p class="text-slate-500 text-sm">Access peer tutors and boost your grades.</p>
                    </div>
                </a>

                <!-- Register as Tutor -->
                <a href="tutor/register.php" class="group bg-white/40 hover:bg-white/60 backdrop-blur-sm p-6 rounded-[1.5rem] border border-white/50 flex items-center gap-6 text-left transition-all hover:scale-[1.03] active:scale-95">
                    <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-500 group-hover:scale-110 transition">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-lg">I'm a Tutor</h4>
                        <p class="text-slate-500 text-sm">Share your knowledge and earn money.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="../resources/js/auth-utils.js"></script>
    <script src="../resources/js/auth-page.js"></script>
</body>
</html>
