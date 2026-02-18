<?php
session_start();
require_once '../../functions/authFunctions.php';
require_once '../../functions/flash.php';

$email = $_SESSION['unverified_email'] ?? null;

if (!$email) {
    header('Location: ../auth.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - SkillSwap</title>
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
        .otp-input:focus {
            ring: 2px solid #6366f1;
            background: white;
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
                <span class="text-sm font-bold text-slate-600 tracking-wide uppercase">SkillSwap Verification</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Check your email</h1>
            <p class="text-slate-500 font-medium">We've sent a 6-digit code to <br><span class="text-slate-900 font-bold"><?= htmlspecialchars($email) ?></span></p>
        </div>

        <!-- Flash Notifications (Simulation of Email) -->
        <?php $flash = getFlash(); ?>
        <?php if ($flash): ?>
        <div class="mb-8 p-6 rounded-[2rem] border animate-in slide-in-from-top-4 duration-300 flex items-center gap-4 shadow-sm
            <?= $flash['type'] === 'success' ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : '' ?>
            <?= $flash['type'] === 'error' ? 'bg-red-50 border-red-100 text-red-700' : '' ?>
            <?= $flash['type'] === 'info' ? 'bg-indigo-50 border-indigo-100 text-indigo-700' : '' ?>
        ">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center
                <?= $flash['type'] === 'success' ? 'bg-emerald-100' : '' ?>
                <?= $flash['type'] === 'error' ? 'bg-red-100' : '' ?>
                <?= $flash['type'] === 'info' ? 'bg-indigo-100' : '' ?>
            ">
                <?php if ($flash['type'] === 'info'): ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <?php elseif ($flash['type'] === 'error'): ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                <?php endif; ?>
            </div>
            <div class="flex-1">
                <span class="block text-xs font-bold uppercase tracking-wider opacity-60 mb-0.5"><?= strtoupper($flash['type']) ?></span>
                <span class="font-bold leading-tight"><?= $flash['message'] ?></span>
            </div>
        </div>
        <?php endif; ?>

        <div class="glass-modal rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden">
            <form action="../../api/verifyOtp.php" method="POST" class="space-y-8">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                
                <div>
                    <p class="text-sm font-medium text-slate-600 mb-6 text-center">Please enter the 6-digit verification code sent to your email to activate your account.</p>
                    <label class="block text-sm font-bold text-slate-700 mb-4 text-center italic">Verification Code</label>
                    <div class="flex justify-between gap-2" id="otp-container">
                        <input type="text" name="otp" maxlength="6" required 
                               placeholder="000 000"
                               class="w-full text-center px-6 py-6 rounded-2xl border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-indigo-600 bg-white shadow-sm transition-all outline-none text-2xl font-bold tracking-[0.5em] placeholder:text-slate-200">
                    </div>
                </div>

                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-5 rounded-2xl shadow-xl transition-all active:scale-95">
                    Verify Account
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm font-medium text-slate-500">
                    Didn't receive the code? 
                    <button class="text-indigo-600 font-bold hover:underline ml-1">Resend Code</button>
                </p>
            </div>
        </div>

        <div class="mt-10 text-center">
            <a href="../auth.php" class="text-slate-400 hover:text-slate-600 font-bold transition flex items-center justify-center gap-2 text-sm uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Login
            </a>
        </div>
    </main>
</body>
</html>
