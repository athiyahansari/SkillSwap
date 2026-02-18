<?php
session_start();
require_once '../../functions/authFunctions.php';
require_once '../../functions/flash.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard.php');
    exit();
}

$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);

// Generate Timezones
$timezones = timezone_identifiers_list();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Account - SkillSwap</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        html { font-size: 11px !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem !important; line-height: 1.6 !important; }
        .bg-soft-gradient {
            background: radial-gradient(circle at top left, #f3f4ff 0%, #ffffff 50%, #fff7ed 100%);
        }
        .input-group:focus-within label { color: #8b5cf6; }
        .input-group:focus-within .icon { color: #8b5cf6; }
        
        /* Custom scrollbar for timezone dropdown */
        select::-webkit-scrollbar { width: 6px; }
        select::-webkit-scrollbar-track { background: transparent; }
        select::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        
        .tag-input-focus { ring: 2px solid #8b5cf6; background: white; }
    </style>
</head>
<body class="bg-soft-gradient min-h-screen flex flex-col items-center p-6 sm:p-12">

    <!-- Header -->
    <header class="w-full max-w-7xl flex items-center justify-between mb-8 sm:mb-16">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <span class="text-xl font-bold text-slate-900 tracking-tight">SkillSwap</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-slate-500 text-sm font-medium hidden sm:inline">Already a member?</span>
            <a href="../auth.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2.5 rounded-full text-sm font-bold transition shadow-lg shadow-indigo-100">Login</a>
        </div>
    </header>

    <main class="w-full max-w-2xl text-center">
        <!-- Progress Steps -->
        <div class="flex items-center justify-between mb-8 px-2 max-w-xl mx-auto">
            <div class="text-left">
                <p id="step-count" class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest mb-2">Step 1 of 2</p>
                <div class="w-48 sm:w-64 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div id="progress-bar" class="w-1/2 h-full bg-indigo-600 transition-all duration-500"></div>
                </div>
            </div>
            <p id="step-label" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-6">Account Details</p>
        </div>

        <h1 id="main-title" class="text-4xl sm:text-5xl font-extrabold text-slate-900 mb-4 tracking-tight">Create your account</h1>
        <p id="main-subtitle" class="text-slate-500 font-medium mb-12">Join the student community and start swapping skills.</p>

        <!-- Form Start -->
        <form action="../../api/registerLearner.php" method="POST" id="regForm">
            <?php echo csrf_field(); ?>
            
            <!-- Step 1: Account Details -->
            <div id="step-1" class="bg-white rounded-[2.5rem] p-8 sm:p-12 shadow-2xl shadow-indigo-50 border border-slate-50 text-left space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <?php if ($error): ?>
                <div class="bg-red-50 border border-red-100 text-red-600 px-6 py-3 rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-xs font-bold"><?= htmlspecialchars($error) ?></span>
                </div>
                <?php endif; ?>

                <div class="input-group">
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700 mb-3 ml-1 transition-colors">
                        <svg class="w-4 h-4 icon text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Full Name
                    </label>
                    <input type="text" name="name" id="field-name" placeholder="e.g. Alex Johnson" 
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none ring-1 ring-slate-100 focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all outline-none text-slate-700 placeholder:text-slate-300 font-medium">
                </div>

                <div class="input-group">
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700 mb-3 ml-1 transition-colors">
                        <svg class="w-4 h-4 icon text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Personal Email
                    </label>
                    <input type="email" name="email" id="field-email" placeholder="alex@gmail.com" 
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none ring-1 ring-slate-100 focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all outline-none text-slate-700 placeholder:text-slate-300 font-medium">
                </div>

                <div class="input-group">
                    <div class="flex items-center justify-between mb-3 ml-1">
                        <label class="flex items-center gap-2 text-sm font-bold text-slate-700 transition-colors">
                            <svg class="w-4 h-4 icon text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 004.516 4.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            Phone Number
                        </label>
                        <span class="text-[10px] font-bold text-slate-400 uppercase bg-slate-100 px-2 py-0.5 rounded-md">Optional</span>
                    </div>
                    <input type="tel" name="phone" id="field-phone" placeholder="+1 (555) 000-0000" 
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none ring-1 ring-slate-100 focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all outline-none text-slate-700 placeholder:text-slate-300 font-medium">
                </div>

                <div class="input-group relative">
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700 mb-3 ml-1 transition-colors">
                        <svg class="w-4 h-4 icon text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="field-pass" placeholder="Min. 8 characters" 
                               class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none ring-1 ring-slate-100 focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all outline-none text-slate-700 placeholder:text-slate-300 font-medium">
                        <button type="button" onclick="togglePassword('field-pass')" class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    <!-- Password Strength Checklist -->
                    <div class="mt-4 grid grid-cols-2 gap-3 px-1" id="pass-checklist">
                        <div class="flex items-center gap-2" id="req-length">
                            <div class="w-4 h-4 rounded-full border-2 border-slate-200 flex items-center justify-center transition-all bg-white">
                                <svg class="w-2.5 h-2.5 text-white opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 transition-colors">8+ Characters</span>
                        </div>
                        <div class="flex items-center gap-2" id="req-upper">
                            <div class="w-4 h-4 rounded-full border-2 border-slate-200 flex items-center justify-center transition-all bg-white">
                                <svg class="w-2.5 h-2.5 text-white opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 transition-colors">Uppercase</span>
                        </div>
                        <div class="flex items-center gap-2" id="req-lower">
                            <div class="w-4 h-4 rounded-full border-2 border-slate-200 flex items-center justify-center transition-all bg-white">
                                <svg class="w-2.5 h-2.5 text-white opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 transition-colors">Lowercase</span>
                        </div>
                        <div class="flex items-center gap-2" id="req-number">
                            <div class="w-4 h-4 rounded-full border-2 border-slate-200 flex items-center justify-center transition-all bg-white">
                                <svg class="w-2.5 h-2.5 text-white opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 transition-colors">Number</span>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="goToStep(2)" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-[800] py-5 rounded-2xl shadow-xl shadow-indigo-100 transition-all active:scale-95 mt-4">
                    Continue to Step 2
                </button>

                <p class="text-[12px] text-center text-slate-400 font-medium pt-4">
                    By signing up, you agree to our <a href="#" class="text-indigo-600 font-bold hover:underline">Terms of Service</a>.
                </p>
                <div class="text-center pt-2">
                    <p class="text-slate-500 font-medium text-sm">Already have an account? <a href="../auth.php" class="text-indigo-600 font-bold hover:underline">Login instead</a></p>
                </div>
            </div>

            <!-- Step 2: Learning Preferences -->
            <div id="step-2" class="hidden bg-white rounded-[2.5rem] p-8 sm:p-12 shadow-2xl shadow-indigo-50 border border-slate-50 text-left space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                
                <!-- Educational Level -->
                <div class="input-group">
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700 mb-3 ml-1 transition-colors">
                        <svg class="w-4 h-4 icon text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                        Educational Level
                    </label>
                    <div class="relative group">
                        <select name="education_level" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none ring-1 ring-slate-100 focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all outline-none text-slate-700 font-medium appearance-none">
                            <option value="">Select your level</option>
                            <option value="secondary">Secondary School</option>
                            <option value="high_school">High School</option>
                            <option value="undergraduate">Undergraduate Degree</option>
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Subjects of Interest -->
                <div class="input-group">
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700 mb-3 ml-1 transition-colors">
                        <svg class="w-4 h-4 icon text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Subjects of Interest
                    </label>
                    <div id="tag-container" class="w-full min-h-[56px] px-4 py-3 rounded-2xl bg-slate-50 border-none ring-1 ring-slate-100 focus-within:ring-2 focus-within:ring-indigo-600 focus-within:bg-white transition-all flex flex-wrap gap-2 items-center">
                        <span class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded-lg text-sm font-bold flex items-center gap-2">
                            Mathematics <button type="button" class="hover:text-indigo-800">×</button>
                        </span>
                        <span class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded-lg text-sm font-bold flex items-center gap-2">
                            Physics <button type="button" class="hover:text-indigo-800">×</button>
                        </span>
                        <input type="text" id="tag-input" placeholder="Type to add..." class="bg-transparent border-none outline-none text-sm font-medium text-slate-700 min-w-[120px] flex-grow">
                        <!-- Hidden input to store subjects for form submission -->
                        <input type="hidden" name="subjects" id="subjects-hidden" value="Mathematics,Physics">
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium mt-3 ml-1">Select multiple subjects you want to learn.</p>
                </div>

                <!-- Time Zone -->
                <div class="input-group">
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700 mb-3 ml-1 transition-colors">
                        <svg class="w-4 h-4 icon text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Time Zone
                    </label>
                    <div class="relative group">
                        <select name="timezone" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none ring-1 ring-slate-100 focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all outline-none text-slate-700 font-medium appearance-none">
                            <option value="">Select your timezone</option>
                            <?php foreach ($timezones as $tz): ?>
                                <option value="<?= htmlspecialchars($tz) ?>" <?= $tz === 'America/New_York' ? 'selected' : '' ?>>(GMT<?= (new DateTime('now', new DateTimeZone($tz)))->format('P') ?>) <?= htmlspecialchars($tz) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button type="button" onclick="goToStep(1)" class="w-full sm:w-1/3 bg-white border border-slate-200 text-slate-600 font-bold py-5 rounded-2xl hover:bg-slate-50 transition active:scale-95">
                        Back
                    </button>
                    <button type="submit" class="w-full sm:w-2/3 bg-indigo-600 hover:bg-indigo-700 text-white font-[800] py-5 rounded-2xl shadow-xl shadow-indigo-100 transition-all active:scale-95 flex items-center justify-center gap-3">
                        Complete Registration
                    </button>
                </div>

                <div class="text-center pt-4">
                    <p class="text-slate-400 font-bold text-sm">Need help? <a href="#" class="text-indigo-600 hover:underline">Contact Support</a></p>
                </div>
            </div>
        </form>

        <footer class="mt-16 pb-8 text-[11px] font-bold text-slate-300 uppercase tracking-widest">
            © 2026 SkillSwap Inc. Designed for creators of the future.
        </footer>
    </main>

    <script src="../../resources/js/auth-utils.js"></script>
    <script src="../../resources/js/register_learner.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Password Validation
            initPasswordValidation({
                inputId: 'field-pass',
                activeClass: 'indigo-600',
                requirements: {
                    length: 'req-length',
                    upper: 'req-upper',
                    lower: 'req-lower',
                    number: 'req-number'
                }
            });

            <?php if ($error && isset($_POST['education_level'])): ?>
                goToStep(2);
            <?php endif; ?>
        });
    </script>
</body>
</html>
