<?php
session_start();
require_once '../../functions/authFunctions.php';
require_once '../../functions/flash.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - SkillSwap</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        html { font-size: 11px !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem !important; line-height: 1.6 !important; }
        .bg-soft-gradient {
            background: radial-gradient(circle at top right, #f8faff 0%, #ffffff 40%, #fffbf8 100%),
                        linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%);
        }
        .glass-modal {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body class="bg-soft-gradient min-h-screen flex flex-col items-center justify-center p-6 relative overflow-x-hidden">

    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-100/40 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-orange-100/30 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <main class="w-full max-w-md">
        <!-- Brand Label -->
        <div class="flex flex-col items-center mb-10 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/50 backdrop-blur-md border border-white/20 shadow-sm mb-6">
                <div class="w-6 h-6 bg-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-100">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-600 tracking-wide uppercase">SkillSwap</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Forgot Password</h1>
            <p class="text-slate-500 font-medium">Enter your email and we'll send you a reset link.</p>
        </div>

        <!-- Flash Notifications -->
        <?php $flash = getFlash(); ?>
        <?php if ($flash): ?>
        <div class="mb-8 p-6 rounded-[2rem] border animate-in slide-in-from-top-4 duration-300 flex items-center gap-4 shadow-sm
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

        <div class="glass-modal rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden">
            <form action="../../api/forgotPassword.php" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email Address</label>
                    <input type="email" name="email" required placeholder="name@university.edu" 
                           class="w-full px-6 py-4 rounded-2xl border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-indigo-600 bg-white shadow-sm transition-all outline-none placeholder:text-slate-300">
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-5 rounded-2xl shadow-xl shadow-indigo-100 transition-all active:scale-95">
                    Send Reset Link
                </button>
            </form>
        </div>

        <div class="mt-8 text-center">
            <a href="../auth.php" class="text-indigo-600 hover:text-indigo-700 font-bold transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Login
            </a>
        </div>
    </main>
</body>
</html>
