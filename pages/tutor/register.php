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
    <title>Tutor Application - SkillSwap</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        html { font-size: 11px !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fafafa; font-size: 1rem !important; line-height: 1.6 !important; }
        .accent-orange { color: #f59e0b; }
        .bg-accent-orange { background-color: #f59e0b; }
        .border-accent-orange { border-color: #f59e0b; }
        .focus-ring-orange:focus { flex-grow: 1; border-color: #f59e0b; }
        
        /* Grid Slot Styles */
        .slot-active { background-color: #f59e0b; border-color: #f59e0b; }
        .slot-inactive { background-color: transparent; border-color: #e2e8f0; }
        
        /* Custom scrollbar */
        select::-webkit-scrollbar { width: 6px; }
        select::-webkit-scrollbar-track { background: transparent; }
        select::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center">

    <!-- Header -->
    <header class="w-full max-w-7xl px-6 py-6 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <span class="text-xl font-extrabold text-slate-900 tracking-tight">SkillSwap</span>
        </div>
        <div class="flex items-center gap-8">
            <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-slate-500">
                <a href="#" class="hover:text-orange-500 transition">Tutor Benefits</a>
                <a href="#" class="hover:text-orange-500 transition">Success Stories</a>
            </nav>
            <div class="flex items-center gap-3">
                <a href="../auth.php" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2.5 rounded-lg text-sm font-bold transition">Sign In</a>
                <button class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-2.5 rounded-lg text-sm font-bold transition">Help</button>
            </div>
        </div>
    </header>

    <main class="w-full max-w-4xl px-6 pt-16 pb-20 text-center">
        <!-- Hero Titles (Change based on Phase) -->
        <h1 id="main-title" class="text-5xl sm:text-6xl font-[800] text-slate-900 mb-6 tracking-tight leading-[1.1]">
            Share your expertise.<br>
            Shape the future.
        </h1>
        <p id="main-subtitle" class="text-slate-400 text-lg font-medium max-w-2xl mx-auto mb-16 leading-relaxed">
            Join our community of elite tutors and start earning on your own schedule. Fill out the application below to get started.
        </p>

        <!-- Progress Header -->
        <div class="w-full bg-white rounded-3xl p-8 border border-slate-100 shadow-sm mb-8 text-left">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Registration Progress</h3>
                <span id="phase-count" class="text-xs font-bold text-slate-400">Step 1 of 4</span>
            </div>
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden mb-4">
                <div id="progress-bar" class="w-1/4 h-full bg-orange-400 transition-all duration-500"></div>
            </div>
            <p class="text-sm font-bold text-orange-400">Current: <span id="phase-label" class="text-slate-500">Personal Information & Expertise</span></p>
        </div>

        <form action="../../api/registerTutor.php" method="POST" id="tutorForm" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-2xl flex items-center gap-3 mb-8 text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-xs font-bold"><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>

            <!-- Phase 1: Personal Info & Expertise -->
            <div id="phase-1" class="phase-container bg-white rounded-[2.5rem] shadow-2xl shadow-orange-50/50 border border-slate-100 overflow-hidden text-left">
                <div class="p-8 sm:p-12 space-y-10">
                    <div>
                        <label class="block text-sm font-extrabold text-slate-800 mb-4 ml-1">Full Name</label>
                        <input type="text" name="name" id="field-name" required placeholder="e.g. Jane Doe" 
                               class="w-full px-8 py-5 rounded-2xl bg-white border border-slate-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-50 outline-none transition-all placeholder:text-slate-300 font-medium">
                    </div>
                    <!-- Credentials -->
                    <div class="grid sm:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-extrabold text-slate-800 mb-4 ml-1">Personal Email</label>
                            <input type="email" name="email" id="field-email" required placeholder="jane.doe@example.com" 
                                   class="w-full px-8 py-5 rounded-2xl bg-white border border-slate-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-50 outline-none transition-all placeholder:text-slate-300 font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-extrabold text-slate-800 mb-4 ml-1">Account Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="tutor-pass" required placeholder="Min. 8 characters" 
                                       class="w-full px-8 py-5 rounded-2xl bg-white border border-slate-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-50 outline-none transition-all placeholder:text-slate-300 font-medium">
                                <button type="button" onclick="togglePassword('tutor-pass')" class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 hover:text-orange-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </div>
                            <!-- Password Strength Checklist -->
                            <div class="mt-5 grid grid-cols-2 gap-4 px-1" id="pass-checklist">
                                <div class="flex items-center gap-3" id="req-length">
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center transition-all bg-white shadow-sm">
                                        <svg class="w-3 h-3 text-white opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400 transition-colors uppercase tracking-wider">8+ Chars</span>
                                </div>
                                <div class="flex items-center gap-3" id="req-upper">
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center transition-all bg-white shadow-sm">
                                        <svg class="w-3 h-3 text-white opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400 transition-colors uppercase tracking-wider">Uppercase</span>
                                </div>
                                <div class="flex items-center gap-3" id="req-lower">
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center transition-all bg-white shadow-sm">
                                        <svg class="w-3 h-3 text-white opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400 transition-colors uppercase tracking-wider">Lowercase</span>
                                </div>
                                <div class="flex items-center gap-3" id="req-number">
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center transition-all bg-white shadow-sm">
                                        <svg class="w-3 h-3 text-white opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400 transition-colors uppercase tracking-wider">Number</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-extrabold text-slate-800 mb-4 ml-1">Academic Subject Expertise</label>
                        <div class="relative group">
                            <select name="subject" class="w-full px-8 py-5 rounded-2xl bg-white border border-slate-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-50 outline-none appearance-none transition-all font-medium text-slate-500 group-focus-within:text-slate-900">
                                <option value="" disabled selected>Select your primary subject</option>
                                <option value="Computer Science">Computer Science & IT</option>
                                <option value="Business">Business & Economics</option>
                                <option value="Humanities">Humanities & Social Sciences</option>
                                <option value="Languages">Languages</option>
                                <option value="Test Prep">Standardized Test Prep</option>
                                <option value="Primary Education">Primary / Junior School</option>
                            </select>
                            <div class="absolute right-8 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-extrabold text-slate-800 mb-4 ml-1">Hourly Rate (USD)</label>
                        <div class="relative">
                            <span class="absolute left-8 top-1/2 -translate-y-1/2 text-orange-500 font-bold">$</span>
                            <input type="number" name="rate" placeholder="45.00" step="0.01" 
                                   class="w-full pl-12 pr-24 py-5 rounded-2xl bg-white border border-slate-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-50 outline-none transition-all placeholder:text-slate-300 font-bold text-slate-700">
                            <span class="absolute right-8 top-1/2 -translate-y-1/2 text-slate-300 font-bold text-sm">per hour</span>
                        </div>
                        <p class="text-[11px] font-bold text-slate-400 mt-3 ml-1">Top tutors usually start between $35 and $60/hr.</p>
                    </div>
                    <button type="button" onclick="goToStep(2)" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-[800] py-6 rounded-2xl shadow-xl shadow-orange-100 flex items-center justify-center gap-3 transition-all active:scale-[0.98]">
                        Apply to be a Tutor
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
                <div class="bg-slate-50/50 px-8 py-6 border-t border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Your data is encrypted and secure</span>
                    </div>
                    <div class="flex gap-3">
                        <div class="bg-slate-200 px-3 py-1.5 rounded-lg text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Verified Partner</div>
                    </div>
                </div>
            </div>

            <!-- Phase 2: Academic Qualifications -->
            <div id="phase-2" class="phase-container hidden bg-white rounded-[2.5rem] shadow-2xl shadow-orange-50/50 border border-slate-100 overflow-hidden text-left animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="p-8 sm:p-12 space-y-10">
                    <div>
                        <label class="block text-sm font-extrabold text-slate-800 mb-4 ml-1">University Name</label>
                        <input type="text" name="university_name" placeholder="e.g. Stanford University" 
                               class="w-full px-8 py-5 rounded-2xl bg-white border border-slate-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-50 outline-none transition-all placeholder:text-slate-300 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-extrabold text-slate-800 mb-4 ml-1">Degree Level</label>
                        <div class="relative group">
                            <select name="qualification" class="w-full px-8 py-5 rounded-2xl bg-white border border-slate-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-50 outline-none appearance-none transition-all font-medium text-slate-500 group-focus-within:text-slate-900">
                                <option value="" disabled selected>Select your highest degree level</option>
                                <option value="Bachelors">Bachelor's Degree</option>
                                <option value="Masters">Master's Degree</option>
                                <option value="PhD">Doctorate / PhD</option>
                            </select>
                            <div class="absolute right-8 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-extrabold text-slate-800 mb-4 ml-1">Transcripts or Certifications</label>
                        <div onclick="triggerUpload('transcripts')" class="w-full px-8 py-10 rounded-2xl bg-slate-50/50 border-2 border-dashed border-slate-200 hover:border-orange-300 transition-colors flex flex-col items-center justify-center cursor-pointer group">
                            <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                            </div>
                            <p class="text-sm font-bold text-slate-900 mb-1">Drop your official transcripts here or <span class="text-orange-500">browse files</span></p>
                            <p class="text-[11px] font-bold text-slate-400">Supports PDF, JPG, or PNG (Max 10MB)</p>
                            <input type="file" id="transcripts" name="transcripts" class="hidden" accept=".pdf,.jpg,.png" onchange="handleFile(this, 'transcript-status')">
                            <p id="transcript-status" class="text-xs font-bold text-orange-500 mt-2 hidden"></p>
                        </div>
                        <p class="text-[11px] font-bold text-slate-400 mt-4">Ensure your name and institution are clearly visible.</p>
                    </div>
                    <div class="flex gap-4">
                        <button type="button" onclick="goToStep(1)" class="w-1/4 bg-white border border-slate-200 text-slate-500 font-bold py-6 rounded-2xl hover:bg-slate-50 transition active:scale-[0.98]">Back</button>
                        <button type="button" onclick="goToStep(3)" class="w-3/4 bg-orange-500 hover:bg-orange-600 text-white font-[800] py-6 rounded-2xl shadow-xl shadow-orange-100 flex items-center justify-center gap-3 transition-all active:scale-[0.98]">
                            Continue to Step 3
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>
                <div class="bg-slate-50/50 px-8 py-6 border-t border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.9L10 9.503l7.834-4.603a2.008 2.008 0 00-2.144-.136L10 7.425 4.31 4.764a2.008 2.008 0 00-2.144.136z"></path><path d="M10 9.503L2.166 4.9A2.01 2.01 0 002 6.007v7.986c0 .59.257 1.14.704 1.516a2.011 2.011 0 002.396.103L10 12.57l4.9 3.042a2.011 2.011 0 002.396-.103 2.012 2.012 0 00.704-1.516V6.007c0-.41-.125-.81-.366-1.107L10 9.503z"></path></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Documents are stored secure & encrypted</span>
                    </div>
                    <div class="flex gap-3">
                        <div class="bg-slate-200 px-3 py-1.5 rounded-lg text-[10px] font-extrabold text-slate-500 uppercase tracking-widest text-[9px]">GDPR COMPLIANT</div>
                    </div>
                </div>
            </div>

            <!-- Phase 3: Availability & Schedule -->
            <div id="phase-3" class="phase-container hidden bg-white rounded-[2.5rem] shadow-2xl shadow-orange-50/50 border border-slate-100 overflow-hidden text-left animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="p-8 sm:p-12 space-y-10">
                    <div>
                        <label class="flex items-center gap-2 text-sm font-extrabold text-slate-800 mb-6">
                            <span class="w-5 h-5 text-orange-500"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg></span>
                            Select Your Timezone
                        </label>
                        <div class="relative group">
                            <select name="timezone" class="w-full px-8 py-5 rounded-2xl bg-white border border-slate-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-50 outline-none appearance-none transition-all font-medium text-slate-500">
                                <option value="">Select your timezone</option>
                                <?php foreach ($timezones as $tz): ?>
                                    <option value="<?= htmlspecialchars($tz) ?>">(GMT<?= (new DateTime('now', new DateTimeZone($tz)))->format('P') ?>) <?= htmlspecialchars($tz) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="absolute right-8 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-8">
                            <label class="flex items-center gap-2 text-sm font-extrabold text-slate-800">
                                <span class="w-5 h-5 text-orange-500"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg></span>
                                Weekly Availability
                            </label>
                            <div class="flex items-center gap-6 text-[10px] font-bold uppercase tracking-widest">
                                <div class="flex items-center gap-2"><span class="w-3 h-3 bg-orange-500 rounded-sm"></span> Available</div>
                                <div class="flex items-center gap-2"><span class="w-3 h-3 bg-white border border-slate-200 rounded-sm"></span> Unavailable</div>
                            </div>
                        </div>
                        
                        <!-- Availability Grid -->
                        <div class="overflow-x-auto pb-4">
                            <div class="min-w-[600px] grid grid-cols-8 gap-1.5 text-center">
                                <div></div>
                                <?php foreach (['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'] as $day): ?>
                                    <div class="text-[10px] font-extrabold text-slate-400 mb-2"><?= $day ?></div>
                                <?php endforeach; ?>

                                <?php foreach (['08:00 AM', '10:00 AM', '12:00 PM', '02:00 PM', '04:00 PM', '06:00 PM'] as $time): ?>
                                    <div class="text-[10px] font-bold text-slate-400 flex items-center justify-end pr-4 h-12"><?= $time ?></div>
                                    <?php for ($i = 0; $i < 7; $i++): ?>
                                        <div onclick="toggleSlot(this)" class="h-12 rounded-lg border-2 border-slate-100 cursor-pointer transition-all hover:border-orange-200 slot-inactive"></div>
                                    <?php endfor; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 mt-6 flex items-center gap-2">
                             <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                             Click on time slots to toggle your availability. Each slot represents 2 hours.
                        </p>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" onclick="goToStep(2)" class="w-1/4 bg-white border border-slate-200 text-slate-500 font-bold py-6 rounded-2xl hover:bg-slate-50 transition active:scale-[0.98]">Back</button>
                        <button type="button" onclick="goToStep(4)" class="w-3/4 bg-orange-500 hover:bg-orange-600 text-white font-[800] py-6 rounded-2xl shadow-xl shadow-orange-100 flex items-center justify-center gap-3 transition-all active:scale-[0.98]">
                            Continue to Final Step
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>
                <div class="bg-slate-50/50 px-8 py-6 border-t border-slate-100">
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Your schedule will only be visible to registered students</span>
                    </div>
                </div>
            </div>

            <!-- Phase 4: Final Review & Payout -->
            <div id="phase-4" class="phase-container hidden bg-white rounded-[2.5rem] shadow-2xl shadow-orange-50/50 border border-slate-100 overflow-hidden text-left animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="p-8 sm:p-12 space-y-10">
                    <div>
                        <h4 class="text-sm font-extrabold text-slate-800 mb-6 uppercase tracking-wider italic">Application Summary</h4>
                        <div class="grid sm:grid-cols-2 gap-8 bg-slate-50/50 p-8 rounded-3xl border border-slate-100">
                            <div class="space-y-6">
                                <div>
                                    <p class="text-[10px] font-extrabold text-orange-400 uppercase">Fullname</p>
                                    <p id="summary-name" class="font-bold text-slate-800">Jane Doe</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-extrabold text-orange-400 uppercase">Hourly Rate</p>
                                    <p id="summary-rate" class="font-bold text-slate-800">$45.00 / hour</p>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <p class="text-[10px] font-extrabold text-orange-400 uppercase">Expertise</p>
                                    <p id="summary-subject" class="font-bold text-slate-800">Mathematics (Advanced Calculus)</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-extrabold text-orange-400 uppercase">Verification Status</p>
                                    <p class="text-green-500 font-bold flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        ID Uploaded
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-extrabold text-slate-800 mb-6 uppercase tracking-wider italic">Payout Method</h4>
                        <div class="grid sm:grid-cols-2 gap-6">
                            <label class="cursor-pointer group">
                                <input type="radio" name="payout" value="bank" checked class="hidden peer">
                                <div class="p-6 rounded-2xl border-2 border-slate-100 peer-checked:border-orange-500 peer-checked:bg-orange-50/30 transition-all flex items-center gap-4">
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 peer-checked:border-orange-500 flex items-center justify-center">
                                        <div class="w-2.5 h-2.5 bg-orange-500 rounded-full opacity-0 peer-checked:opacity-100"></div>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">Bank Transfer</p>
                                        <p class="text-[10px] font-medium text-slate-400">Direct deposit to your bank account</p>
                                    </div>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="payout" value="paypal" class="hidden peer">
                                <div class="p-6 rounded-2xl border-2 border-slate-100 peer-checked:border-orange-500 peer-checked:bg-orange-50/30 transition-all flex items-center gap-4">
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 peer-checked:border-orange-500 flex items-center justify-center">
                                        <div class="w-2.5 h-2.5 bg-orange-500 rounded-full opacity-0 peer-checked:opacity-100"></div>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">PayPal</p>
                                        <p class="text-[10px] font-medium text-slate-400">Instant payout to your email</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-extrabold text-slate-800 mb-6 uppercase tracking-wider italic">Account Details</h4>
                        <textarea name="payout_details" placeholder="Routing or Email address" rows="2" 
                                  class="w-full px-8 py-5 rounded-2xl bg-white border border-slate-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-50 outline-none transition-all placeholder:text-slate-300 font-medium"></textarea>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" onclick="goToStep(3)" class="w-1/4 bg-white border border-slate-200 text-slate-500 font-bold py-6 rounded-2xl hover:bg-slate-50 transition active:scale-[0.98]">Back</button>
                        <button type="submit" class="w-3/4 bg-orange-500 hover:bg-orange-600 text-white font-[800] py-6 rounded-2xl shadow-xl shadow-orange-100 flex items-center justify-center gap-3 transition-all active:scale-[0.98]">
                            Submit Application
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                </div>
                <div class="bg-slate-50/50 px-8 py-6 border-t border-slate-100 flex justify-between">
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.9L10 9.503l7.834-4.603a2.008 2.008 0 00-2.144-.136L10 7.425 4.31 4.764a2.008 2.008 0 00-2.144.136z"></path><path d="M10 9.503L2.166 4.9A2.01 2.01 0 002 6.007v7.986c0 .59.257 1.14.704 1.516a2.011 2.011 0 002.396.103L10 12.57l4.9 3.042a2.011 2.011 0 002.396-.103 2.012 2.012 0 00.704-1.516V6.007c0-.41-.125-.81-.366-1.107L10 9.503z"></path></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Secured by industry standard encryption</span>
                    </div>
                    <div class="flex gap-3">
                        <div class="bg-slate-200 px-3 py-1.5 rounded-lg text-[10px] font-extrabold text-slate-500 uppercase tracking-widest text-[9px]">PCI COMPLIANT</div>
                    </div>
                </div>
            </div>

            <!-- Hidden inputs for Availability -->
            <input type="hidden" name="availability" id="availability-hidden">
        </form>

        <!-- Secondary Info Cards -->
        <div id="footer-cards" class="grid sm:grid-cols-2 gap-8 mt-12 mb-20">
            <!-- Phase 1/2 specific cards -->
            <div id="card-1" class="bg-white p-8 rounded-3xl border border-slate-100 flex gap-6 text-left items-start">
                <div class="w-14 h-14 bg-orange-100 text-orange-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.121V12a1 1 0 001 1h8a1 1 0 001-1V10.12l1.69-.724a1 1 0 011.31 1.39l-7 7a1 1 0 01-1.39 0l-7-7a1 1 0 011.39-1.39z"></path></svg>
                </div>
                <div>
                    <h4 id="card-1-title" class="font-extrabold text-slate-900 mb-2">Academic Standards</h4>
                    <p id="card-1-text" class="text-sm font-medium text-slate-400 leading-relaxed">We maintain high standards. Ensure your ID matches your subject expertise.</p>
                </div>
            </div>
            <div id="card-2" class="bg-white p-8 rounded-3xl border border-slate-100 flex gap-6 text-left items-start">
                <div class="w-14 h-14 bg-orange-100 text-orange-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <h4 id="card-2-title" class="font-extrabold text-slate-900 mb-2">Weekly Payouts</h4>
                    <p id="card-2-text" class="text-sm font-medium text-slate-400 leading-relaxed">Direct deposit every Friday for all completed sessions from the previous week.</p>
                </div>
            </div>
        </div>

        <p class="text-slate-500 font-bold text-sm mb-12">Already have an account? <a href="../auth.php" class="text-orange-500 hover:underline">Login instead</a></p>

        <!-- Footer -->
        <footer class="pt-12 border-t border-slate-100 w-full">
            <div class="flex items-center justify-center gap-2 mb-6">
                <div class="w-6 h-6 bg-orange-500 rounded-md flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-900 tracking-tight">SkillSwap</span>
            </div>
            <div class="text-[12px] font-bold text-slate-400 flex flex-wrap justify-center gap-x-8 gap-y-4 mb-4">
                <span>© 2026 SkillSwap Inc. All rights reserved.</span>
                <a href="#" class="hover:text-slate-600 transition">Privacy Policy</a>
                <a href="#" class="hover:text-slate-600 transition">Terms of Service</a>
            </div>
        </footer>
    </main>

    <script src="../../resources/js/auth-utils.js?v=<?= time() ?>"></script>
    <script>
        // Initialize Password Validation
        initPasswordValidation({
            inputId: 'tutor-pass',
            activeClass: 'orange-500',
            requirements: {
                length: 'req-length',
                upper: 'req-upper',
                lower: 'req-lower',
                number: 'req-number'
            }
        });
    </script>
    <script src="../../resources/js/register_tutor.js?v=<?= time() ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
             // Pass error state if needed
             <?php if ($error && isset($_POST['university_name'])): ?>
                goToStep(2);
             <?php endif; ?>
        });
    </script>
</body>
</html>
