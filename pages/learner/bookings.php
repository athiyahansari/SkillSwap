<?php
session_start();

/**
 * Learner Bookings View
 * Read-only page displaying all sessions booked by the learner.
 */

require_once '../../functions/db.php';
require_once '../../functions/authFunctions.php';
require_once '../../functions/learnerFunctions.php';

// Security check: Ensure user is logged in and is a learner
if (!requireAuth('learner')) {
    header('Location: ../auth.php');
    exit();
}

$userId = $_SESSION['user_id'];
$bookings = getLearnerBookings($userId);

// Fetch user profile for sidebar name display
$profile = getLearnerProfile($userId);
$userName = explode(' ', $profile['name'] ?? 'Learner')[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - SkillSwap</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        html { font-size: 11px !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F9FD; font-size: 1rem !important; line-height: 1.6 !important; }
        .sidebar-item-active { background-color: #EFEEFE; color: #6366F1; }
        .sidebar-item-active svg { color: #6366F1; }
    </style>
</head>
<body class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-72 bg-white border-r border-slate-100 flex flex-col fixed h-full z-10">
        <div class="px-8 py-10 flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <span class="text-2xl font-extrabold text-slate-900 tracking-tight">SkillSwap</span>
        </div>

        <nav class="flex-1 px-4 space-y-2">
            <a href="dashboard.php" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-slate-500 hover:bg-slate-50 transition-all group">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Find a Tutor
            </a>
            <a href="bookings.php" class="sidebar-item-active flex items-center gap-4 px-6 py-4 rounded-2xl font-bold transition-all group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                My Bookings
            </a>
            <a href="profile.php" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-slate-500 hover:bg-slate-50 transition-all group">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Settings
            </a>
        </nav>

        <div class="px-6 py-10 border-t border-slate-100 mt-auto">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Alex" alt="Avatar" class="w-10 h-10 rounded-full bg-slate-100 border-2 border-slate-50">
                    <div>
                        <h4 class="text-sm font-extrabold text-slate-900"><?= htmlspecialchars($userName) ?></h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Student Account</p>
                    </div>
                </div>
            </div>
            <a href="../../api/logout.php" class="flex items-center gap-3 px-6 py-3 rounded-xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all font-bold text-xs group">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Sign Out
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-72 p-12">
        <header class="mb-12 flex items-center justify-between text-sm font-bold">
            <nav>
                <a href="dashboard.php" class="text-slate-400 hover:text-indigo-600 transition-all">Find a Tutor /</a>
                <span class="text-indigo-600">My Bookings</span>
            </nav>
        </header>

        <header class="mb-12">
            <h1 class="text-4xl font-[800] text-slate-900 mb-2">My Bookings</h1>
            <p class="text-slate-400 font-medium">Keep track of your learning journey and upcoming sessions.</p>
        </header>

        <!-- Bookings List -->
        <?php if (empty($bookings)): ?>
            <div class="bg-white rounded-[2.5rem] p-20 text-center border border-slate-50 shadow-sm mt-12">
                <div class="w-20 h-20 bg-indigo-50 text-indigo-500 rounded-3xl flex items-center justify-center mx-auto mb-8">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-900 mb-4">You have no bookings yet.</h3>
                <p class="text-slate-500 font-medium max-w-md mx-auto leading-relaxed mb-10">
                    Connect with expert tutors to start your academic adventure and master any subject you desire.
                </p>
                <a href="dashboard.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-black shadow-lg shadow-indigo-100 transition-all active:scale-95 inline-flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Find a Tutor Now
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 gap-6">
                <?php foreach ($bookings as $booking): ?>
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-50 flex items-center justify-between group hover:shadow-xl hover:shadow-indigo-50/50 hover:border-indigo-100 transition-all duration-300">
                    <div class="flex items-center gap-8">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-xl">
                            <?= strtoupper(substr($booking['tutor_name'] ?? 'T', 0, 1)) ?>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="font-extrabold text-lg text-slate-900"><?= htmlspecialchars($booking['tutor_name']) ?></h3>
                                <span class="text-slate-300">|</span>
                                <span class="text-sm font-bold text-slate-500"><?= htmlspecialchars($booking['subject_name'] ?? 'General') ?></span>
                            </div>
                            <div class="flex items-center gap-6 text-sm font-bold text-slate-400">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <?= date('M d, Y', strtotime($booking['start_time'])) ?>
                                </span>
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <?= date('g:i A', strtotime($booking['start_time'])) ?> - <?= date('g:i A', strtotime($booking['end_time'])) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-3 text-right">
                        <?php if ($booking['status'] === 'pending'): ?>
                            <span class="bg-amber-50 text-amber-500 px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-wider flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                Pending
                            </span>
                            <p class="text-xs font-bold text-slate-400 italic">Waiting for tutor confirmation</p>
                        <?php elseif ($booking['status'] === 'confirmed'): ?>
                            <span class="bg-emerald-50 text-emerald-600 px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-wider flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                Confirmed
                            </span>
                            <?php if (strtotime($booking['end_time']) < time() && !$booking['has_review']): ?>
                                <button onclick="openReviewModal(<?= $booking['booking_id'] ?>, '<?= htmlspecialchars($booking['tutor_name']) ?>')" class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-indigo-700 transition-all shadow-md active:scale-95">
                                    Leave Review
                                </button>
                            <?php elseif ($booking['has_review']): ?>
                                <p class="text-xs font-bold text-emerald-500">Review Submitted</p>
                            <?php else: ?>
                                <p class="text-xs font-bold text-indigo-500">Upcoming class</p>
                            <?php endif; ?>
                        <?php elseif ($booking['status'] === 'completed'): ?>
                             <span class="bg-indigo-50 text-indigo-600 px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-wider flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
                                Completed
                            </span>
                            <p class="text-xs font-bold text-emerald-500">Review/Rating Submitted</p>
                        <?php elseif ($booking['status'] === 'cancelled'): ?>
                            <span class="bg-red-50 text-red-500 px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-wider flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                Cancelled
                            </span>
                            <p class="text-xs font-bold text-slate-400 italic">Booking cancelled</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Review Modal -->
    <div id="review-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-6 backdrop-blur-md bg-slate-900/40 animate-in fade-in duration-300">
        <div class="bg-white/80 backdrop-blur-2xl border border-white rounded-[2.5rem] shadow-2xl shadow-indigo-500/10 max-w-md w-full p-10 animate-in zoom-in-95 duration-300">
            <h3 class="text-2xl font-extrabold text-slate-900 mb-2 tracking-tight">Rate Your Session</h3>
            <p id="review-tutor-name" class="text-indigo-600 font-bold mb-8"></p>
            
            <form action="../../api/submitReview.php" method="POST" id="review-form">
                <input type="hidden" name="booking_id" id="modal-booking-id">
                
                <div class="mb-8">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 text-center">Your Rating</label>
                    <div class="flex items-center justify-center gap-4">
                        <?php for($i=1; $i<=5; $i++): ?>
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="rating" value="<?= $i ?>" class="hidden peer" required>
                            <svg class="star-icon w-10 h-10 text-slate-200 group-hover:text-amber-300 transition-all cursor-pointer" fill="currentColor" stroke="#94a3b8" stroke-width="1.5" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </label>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="mb-10">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Your Experience (Optional)</label>
                    <textarea name="comment" rows="4" class="w-full bg-slate-50 border border-slate-100 rounded-2xl p-5 text-sm font-bold text-slate-600 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all placeholder:text-slate-300" placeholder="How was your lesson? Any feedback for the tutor?"></textarea>
                </div>

                <div class="flex items-center gap-4">
                    <button type="button" onclick="closeReviewModal()" class="flex-1 px-8 py-4 rounded-2xl bg-white border border-slate-100 text-slate-400 font-black hover:bg-slate-50 transition-all active:scale-95">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-8 py-4 rounded-2xl bg-indigo-600 text-white font-black shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openReviewModal(bookingId, tutorName) {
            document.getElementById('modal-booking-id').value = bookingId;
            document.getElementById('review-tutor-name').textContent = "Lesson with " + tutorName;
            const modal = document.getElementById('review-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Reset stars
            updateStars(0);
        }

        function closeReviewModal() {
            const modal = document.getElementById('review-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            currentForm = null;
        }

        // Star Rating Logic
        const stars = document.querySelectorAll('.star-icon');
        const ratingInputs = document.querySelectorAll('input[name="rating"]');
        let currentRating = 0;

        function updateStars(rating, isHover = false) {
            stars.forEach((star, index) => {
                const starIdx = index + 1;
                if (starIdx <= rating) {
                    star.classList.remove('text-slate-200');
                    star.classList.add('text-amber-400', 'scale-110');
                    if (!isHover && starIdx === rating) {
                        star.classList.add('animate-bounce-subtle');
                        setTimeout(() => star.classList.remove('animate-bounce-subtle'), 400);
                    }
                } else {
                    star.classList.add('text-slate-200');
                    star.classList.remove('text-amber-400', 'scale-110', 'text-amber-300');
                }
            });
        }

        ratingInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                currentRating = parseInt(e.target.value);
                updateStars(currentRating);
            });

            const label = input.closest('label');
            label.addEventListener('mouseenter', () => {
                const val = parseInt(input.value);
                updateStars(val, true);
            });

            label.addEventListener('mouseleave', () => {
                updateStars(currentRating);
            });
        });

        // Close on ESC
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeReviewModal();
        });
    </script>

    <style>
        @keyframes bounce-subtle {
            0%, 100% { transform: scale(1.1); }
            50% { transform: scale(1.4); }
        }
        .animate-bounce-subtle {
            animation: bounce-subtle 0.4s ease-out;
        }
        .star-icon {
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
    </style>
</body>
</html>
