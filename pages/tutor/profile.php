<?php
/**
 * Tutor Profile Settings Page
 * Refined UI to match design mockup.
 */

session_start();

// 1. Security check
require_once '../../functions/db.php';
require_once '../../functions/authFunctions.php';
require_once '../../functions/tutorFunctions.php';

if (!requireAuth('tutor')) {
    header('Location: ../auth.php');
    exit();
}

$userId = $_SESSION['user_id'];

// 2. Fetch data via Model
$profile = getTutorProfileByUserId($userId);

if (!$profile) {
    die("Profile not found.");
}

$displayName = $profile['name'];
$subjectName = $profile['subject_name'] ?? 'Expert Tutor';
$isVerified = ($profile['status'] === 'verified');

$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
unset($_SESSION['error'], $_SESSION['success']);

// 3. Parse subjects
$currentSubjects = !empty($profile['subjects']) ? array_map('trim', explode(',', $profile['subjects'])) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - SkillSwap</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        html { font-size: 11px !important; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #FBFBFF; 
            color: #4A4A68;
            font-size: 1rem !important;
            line-height: 1.6 !important;
        }
        .text-skillswap { color: #8B5CF6; }
        .bg-skillswap { background-color: #8B5CF6; }
        .border-skillswap { border-color: #8B5CF6; }
        .shadow-soft { box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05); }
        .form-input {
            background-color: #F8F9FE;
            border: 1px solid #E5E7EB;
            border-radius: 1.25rem;
            padding: 1rem 1.5rem;
            width: 100%;
            transition: all 0.2s;
            outline: none;
        }
        .form-input:focus {
            border-color: #8B5CF6;
            background-color: #FFFFFF;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
        }
        .form-input:disabled {
            background-color: #F3F4F6;
            cursor: not-allowed;
            color: #6B7280;
        }
    </style>
</head>
<body class="min-h-screen">

    <!-- Header -->
    <header class="w-full bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-50">
        <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-skillswap rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="text-xl font-extrabold text-slate-800 tracking-tight">SkillSwap</span>
            </div>
            <a href="dashboard.php" class="bg-indigo-50 text-indigo-600 px-6 py-2.5 rounded-full text-sm font-bold flex items-center gap-2 hover:bg-indigo-100 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-12">
        <!-- Status Banners -->
        <?php if ($profile['status'] === 'rejected'): ?>
        <div class="mb-8 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-4 animate-in slide-in-from-top-4 duration-300">
            <div class="w-10 h-10 bg-red-100 text-red-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <div>
                <h4 class="text-sm font-extrabold text-red-800">Application Rejected</h4>
                <p class="text-xs font-semibold text-red-600/80">Admin has rejected your application. Please contact <a href="mailto:admin@skillswap.com" class="underline font-bold">admin@skillswap.com</a> for more information.</p>
            </div>
        </div>
        <?php elseif ($profile['status'] === 'pending'): ?>
        <div class="mb-8 p-4 bg-amber-50 border border-amber-100 rounded-2xl flex items-center gap-4 animate-in slide-in-from-top-4 duration-300">
            <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h4 class="text-sm font-extrabold text-amber-800">Verification Pending</h4>
                <p class="text-xs font-semibold text-amber-600/80">Your profile is currently waiting for administrator approval. You can still update your details.</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Messages -->
        <?php if ($error): ?>
            <div class="mb-8 p-4 bg-red-50 text-red-600 border border-red-100 rounded-2xl text-xs font-bold animate-pulse flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="mb-8 p-4 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-2xl text-xs font-bold flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Page Title -->
        <div class="mb-12">
            <h1 class="text-4xl font-[800] text-slate-900 mb-2 tracking-tight">Edit Profile</h1>
            <p class="text-slate-400 font-medium">Update your public tutor information so students can find the right match.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Column: Identity Card -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Avatar Card -->
                <div class="bg-white rounded-[2.5rem] p-10 shadow-soft border border-slate-50 text-center flex flex-col items-center">
                    <div class="relative mb-8">
                        <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-slate-50 shadow-inner">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?= urlencode($displayName) ?>" class="w-full h-full object-cover">
                        </div>
                        <?php if ($isVerified): ?>
                        <span class="absolute bottom-2 right-2 bg-skillswap text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tighter shadow-lg ring-4 ring-white">PRO</span>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="text-xl font-[800] text-slate-800 mb-1"><?= htmlspecialchars($displayName) ?></h3>
                    <p class="text-xs font-bold text-skillswap uppercase tracking-widest mb-10"><?= htmlspecialchars($subjectName) ?></p>

                    <button class="w-full py-4 rounded-2xl border-2 border-slate-100 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Change Photo
                    </button>
                </div>
            </div>

            <!-- Right Column: Main Form -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-[2.5rem] p-10 lg:p-14 shadow-soft border border-slate-50">
                    <form action="../../api/updateTutorProfile.php" method="POST" class="space-y-12">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="profile_id" value="<?= htmlspecialchars($profile['profile_id']) ?>">
                        
                        <!-- Top Rows -->
                        <div class="grid sm:grid-cols-2 gap-8 lg:gap-12">
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4 ml-1">Display Name</label>
                                <input type="text" value="<?= htmlspecialchars($displayName) ?>" disabled class="form-input">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4 ml-1">Hourly Rate ($)</label>
                                <div class="relative">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-skillswap font-black">$</span>
                                    <input type="number" name="hourly_rate" step="0.01" value="<?= htmlspecialchars($profile['hourly_rate']) ?>" required
                                           class="form-input pl-11 font-extrabold text-slate-700">
                                </div>
                            </div>
                        </div>

                        <!-- Bio -->
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4 ml-1">Bio / Introduction</label>
                            <textarea name="bio" id="bio-textarea" rows="6" maxlength="500" required 
                                      placeholder="Tell students about your teaching style, experience, and what makes you a great tutor..."
                                      class="form-input resize-none h-48 leading-relaxed font-medium"><?= htmlspecialchars($profile['bio']) ?></textarea>
                            <div class="mt-3 flex justify-end">
                                <span id="char-count" class="text-[10px] font-bold text-slate-300 uppercase tracking-wider">0/500 characters</span>
                            </div>
                        </div>

                        <!-- Skills & Subjects -->
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4 ml-1">Skills & Subjects</label>
                            <div id="subjects-container" class="flex flex-wrap gap-3 p-2 border-2 border-slate-50 rounded-[1.5rem] bg-slate-50/30">
                                <!-- Chips will be injected here -->
                                <input type="text" id="subject-input" placeholder="Type and press Enter..." class="bg-transparent border-none py-2 px-4 outline-none text-xs font-bold text-slate-400 flex-1 min-w-[200px]">
                            </div>
                            <!-- Hidden storage for subjects -->
                            <div id="hidden-subjects-inputs"></div>
                            <p class="mt-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.1em] ml-1">Add specific subjects like 'Linear Algebra' or 'Organic Chemistry' to get better matches.</p>
                        </div>

                        <!-- Actions -->
                        <div class="pt-8 border-t border-slate-50 flex items-center justify-end gap-10">
                            <a href="dashboard.php" class="text-sm font-black text-slate-400 hover:text-red-500 transition-colors">Cancel</a>
                            <button type="submit" class="bg-skillswap hover:bg-skillswap/90 text-white font-black px-12 py-5 rounded-[1.5rem] shadow-xl shadow-skillswap/20 flex items-center gap-3 transition-all active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        const textarea = document.getElementById('bio-textarea');
        const charCount = document.getElementById('char-count');

        function updateCount() {
            const length = textarea.value.length;
            charCount.textContent = `${length}/500 characters`;
            if (length >= 450) charCount.classList.add('text-orange-400');
            else charCount.classList.remove('text-orange-400');
        }

        textarea.addEventListener('input', updateCount);
        updateCount();

        // --- Subject Chips Logic ---
        const subjectsContainer = document.getElementById('subjects-container');
        const subjectInput = document.getElementById('subject-input');
        const hiddenInputsContainer = document.getElementById('hidden-subjects-inputs');
        
        let subjects = <?= json_encode($currentSubjects) ?>;

        function renderChips() {
            // Remove existing chips
            subjectsContainer.querySelectorAll('.subject-chip').forEach(el => el.remove());
            hiddenInputsContainer.innerHTML = '';

            subjects.forEach((subject, index) => {
                // Render UI chip
                const chip = document.createElement('div');
                chip.className = 'subject-chip bg-indigo-50 text-indigo-600 px-5 py-2 rounded-full text-xs font-black flex items-center gap-2 border border-indigo-100 animate-in zoom-in duration-200';
                chip.innerHTML = `${subject} <button type="button" onclick="removeSubject(${index})" class="hover:text-indigo-800 text-base leading-none">×</button>`;
                subjectsContainer.insertBefore(chip, subjectInput);

                // Add hidden input for form submission
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'subjects[]';
                hidden.value = subject;
                hiddenInputsContainer.appendChild(hidden);
            });
        }

        function addSubject(value) {
            const trimmed = value.trim();
            if (trimmed && !subjects.includes(trimmed)) {
                subjects.push(trimmed);
                renderChips();
            }
            subjectInput.value = '';
        }

        window.removeSubject = function(index) {
            subjects.splice(index, 1);
            renderChips();
        }

        subjectInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                addSubject(subjectInput.value);
            }
        });

        // Add on comma or blur too (optional but nice)
        subjectInput.addEventListener('input', (e) => {
            if (e.target.value.endsWith(',')) {
                addSubject(e.target.value.slice(0, -1));
            }
        });

        // Initial render
        renderChips();
    </script>
</body>
</html>
