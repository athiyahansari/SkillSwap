<?php
session_start();

/**
 * Tutor Dashboard Page
 * High-fidelity implementation based on design reference.
 */

// 1. Security check: Ensure user is logged in and is a tutor
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'tutor') {
    header('Location: ../auth.php');
    exit();
}

require_once '../../functions/db.php';
require_once '../../functions/authFunctions.php';
require_once '../../functions/tutorFunctions.php';
require_once '../../functions/flash.php';

$userId = $_SESSION['user_id'];

// 2. Fetch basic user info (name) regardless of profile status
$stmt = $pdo->prepare("SELECT name FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$userBasic = $stmt->fetch();
$displayName = $userBasic['name'] ?? 'Tutor';

$profile = getTutorProfile($userId);

// Sanity Check: Determine if the tutor is rejected or pending
$verificationStatus = $profile['verification_status'] ?? 'pending';
$isRejected = ($profile === false || $verificationStatus === 'rejected');
$isVerified = (!$isRejected && $verificationStatus === 'verified');
$subjectName = $profile['subject_name'] ?? 'Tutor';

$sessions = $isRejected ? [] : getTutorSessions($userId);
$reviews = (!$isRejected && $isVerified) ? getTutorReviews($profile['profile_id']) : [];
$finance = ($isVerified) ? getTutorFinanceSummary($userId) : null;
$totalEarned = $finance['total_earnings'] ?? 0;

// Calculate sessions for today for the welcome banner
$todaySessionsCount = 0;
$todayDate = date('Y-m-d');
if ($sessions) {
    foreach ($sessions as $session) {
        if (date('Y-m-d', strtotime($session['start_time'])) === $todayDate) {
            $todaySessionsCount++;
        }
    }
}

// Format name for display
$firstName = explode(' ', $displayName)[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard - SkillSwap</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        html { font-size: 11px !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F9FD; font-size: 1rem !important; line-height: 1.6 !important; }
        .sidebar-item-active { background-color: #EFEEFE; color: #6366F1; }
        .sidebar-item-active svg { color: #6366F1; }
        .welcome-gradient {
            background: linear-gradient(135deg, #6366F1 0%, #A855F7 100%);
        }
        .stats-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.05), 0 8px 10px -6px rgb(0 0 0 / 0.05);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-72 bg-white border-r border-slate-100 flex flex-col fixed h-full z-20">
        <div class="px-8 py-10 flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <span class="text-2xl font-extrabold text-slate-900 tracking-tight">SkillSwap</span>
        </div>

        <nav class="flex-1 px-4 space-y-2">
            <a href="dashboard.php" class="sidebar-item-active flex items-center gap-4 px-6 py-4 rounded-2xl font-bold transition-all group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Home
            </a>
            <a href="earnings.php" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-slate-500 hover:bg-slate-50 transition-all group">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Earnings
            </a>
            <a href="profile.php" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-slate-500 hover:bg-slate-50 transition-all group">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Profile
            </a>
        </nav>

        <div class="px-6 py-10 border-t border-slate-100 mt-auto">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?= urlencode($displayName) ?>" alt="Avatar" class="w-10 h-10 rounded-full bg-slate-100 border-2 border-slate-50">
                    <div>
                        <h4 class="text-sm font-extrabold text-slate-900"><?= htmlspecialchars($displayName) ?></h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?= htmlspecialchars($subjectName) ?></p>
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
        <!-- Top Nav / Breadcrumbs -->
        <header class="flex items-center justify-between mb-12">
            <nav class="text-sm font-bold">
                <span class="text-slate-400">Dashboard /</span> 
                <span class="text-indigo-600">Overview</span>
            </nav>
            <div class="flex items-center gap-4">
                <button class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm">
                    <span class="relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-400 rounded-full border-2 border-white"></span>
                    </span>
                </button>
            </div>
        </header>

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

        <!-- Welcome Banner -->
        <section class="welcome-gradient rounded-[2.5rem] p-12 mb-12 relative overflow-hidden shadow-2xl shadow-indigo-200">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">Welcome back, <?= htmlspecialchars($firstName) ?>!</h2>
                    <?php if ($isRejected): ?>
                        <p class="text-red-100 text-lg font-bold opacity-90 italic">Your application has been rejected. Please contact support.</p>
                    <?php elseif (!$isVerified): ?>
                        <p class="text-amber-100 text-lg font-medium opacity-90 italic">Application is currently pending administrator verification.</p>
                    <?php else: ?>
                        <p class="text-indigo-100 text-lg font-medium opacity-90">You have <?= $todaySessionsCount ?> sessions scheduled for today.</p>
                    <?php endif; ?>
                </div>
                <?php if (!$isRejected): ?>
                <button onclick="toggleAvailabilityModal()" class="bg-white text-indigo-600 hover:bg-indigo-50 px-8 py-4 rounded-2xl font-bold shadow-lg flex items-center gap-3 transition-all active:scale-95 group">
                    <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Update Availability
                </button>
                <?php else: ?>
                <button disabled title="Profile not approved" class="bg-white/50 text-white/50 cursor-not-allowed px-8 py-4 rounded-2xl font-bold shadow-lg flex items-center gap-3 transition-all">
                    Re-apply (Coming Soon)
                </button>
                <?php endif; ?>
            </div>
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-black/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/4"></div>
        </section>

        <?php if (!$isRejected): ?>

        <!-- Stats Cards -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Total Earnings -->
            <div class="bg-white p-8 rounded-[2rem] border border-slate-50 shadow-sm stats-card flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-black flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path></svg>
                        12%
                    </span>
                </div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Total Earnings</p>
                <h3 class="text-3xl font-black text-slate-900">$<?= number_format($totalEarned, 2) ?></h3>
            </div>

            <!-- Average Rating -->
            <div class="bg-white p-8 rounded-[2rem] border border-slate-50 shadow-sm stats-card flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                </div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Average Rating</p>
                <h3 class="text-3xl font-black text-slate-900"><?= $profile['avg_rating'] ? number_format($profile['avg_rating'], 1) : 'New' ?> <span class="text-sm text-slate-400 font-bold">(<?= $profile['review_count'] ?? 0 ?>)</span></h3>
            </div>
        </section>

        <!-- Upcoming Sessions Table -->
        <section class="bg-white rounded-[2.5rem] border border-slate-50 shadow-sm overflow-hidden mb-12">
            <div class="px-10 py-8 flex items-center justify-between border-b border-slate-50">
                <h3 class="text-xl font-extrabold text-slate-900">Upcoming Sessions</h3>
                <a href="#" class="text-indigo-600 font-bold hover:underline">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-10 py-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Time</th>
                            <th class="px-10 py-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Student</th>
                            <th class="px-10 py-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Subject</th>
                            <th class="px-10 py-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                            <th class="px-10 py-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if (empty($sessions)): ?>
                        <tr>
                            <td colspan="4" class="px-10 py-20 text-center text-slate-400 font-medium">No sessions scheduled yet.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($sessions as $session): 
                                $startTime = strtotime($session['start_time']);
                                $isToday = date('Y-m-d', $startTime) === $todayDate;
                                $timeDisplay = ($isToday ? 'Today' : date('D, M d', $startTime)) . ', ' . date('g:i A', $startTime);
                            ?>
                            <tr class="hover:bg-slate-50/80 transition-all group">
                                <td class="px-10 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 group-hover:text-indigo-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span class="font-bold text-slate-700"><?= htmlspecialchars($timeDisplay) ?></span>
                                    </div>
                                </td>
                                <td class="px-10 py-6">
                                    <div class="flex items-center gap-4">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?= urlencode($session['student_name']) ?>" class="w-10 h-10 rounded-xl bg-slate-100 object-cover">
                                        <span class="font-extrabold text-slate-900"><?= htmlspecialchars($session['student_name']) ?></span>
                                    </div>
                                </td>
                                <td class="px-10 py-6">
                                    <span class="bg-indigo-50 text-indigo-600 px-4 py-1.5 rounded-full text-xs font-black flex items-center w-fit gap-2">
                                        <span class="w-1.5 h-1.5 bg-indigo-600 rounded-full"></span>
                                        <?= htmlspecialchars($session['subject_name'] ?? 'General') ?>
                                    </span>
                                </td>
                                <td class="px-10 py-6">
                                    <?php if ($session['status'] === 'confirmed'): ?>
                                        <span class="bg-emerald-50 text-emerald-600 px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-wider flex items-center w-fit gap-2">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                            Confirmed
                                        </span>
                                    <?php elseif ($session['status'] === 'pending'): ?>
                                        <span class="bg-amber-50 text-amber-500 px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-wider flex items-center w-fit gap-2">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                            Pending
                                        </span>
                                    <?php elseif ($session['status'] === 'cancelled'): ?>
                                        <span class="bg-red-50 text-red-500 px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-wider flex items-center w-fit gap-2">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                            Cancelled
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-10 py-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <?php if ($session['status'] === 'pending'): ?>
                                            <!-- Confirm Action -->
                                            <form action="../../api/updateBookingStatus.php" method="POST" onsubmit="showConfirm(event, 'confirm')">
                                                <input type="hidden" name="booking_id" value="<?= $session['booking_id'] ?>">
                                                <input type="hidden" name="action" value="confirm">
                                                <button type="submit" class="bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all shadow-sm">
                                                    ✔ Confirm
                                                </button>
                                            </form>
                                            <!-- Decline Action -->
                                            <form action="../../api/updateBookingStatus.php" method="POST" onsubmit="showConfirm(event, 'decline')">
                                                <input type="hidden" name="booking_id" value="<?= $session['booking_id'] ?>">
                                                <input type="hidden" name="action" value="decline">
                                                <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all shadow-sm">
                                                    ✖ Decline
                                                </button>
                                            </form>
                                        <?php elseif ($session['status'] === 'confirmed'): ?>
                                            <!-- Cancel Action -->
                                            <form action="../../api/updateBookingStatus.php" method="POST" onsubmit="showConfirm(event, 'cancel')">
                                                <input type="hidden" name="booking_id" value="<?= $session['booking_id'] ?>">
                                                <input type="hidden" name="action" value="cancel">
                                                <button type="submit" class="bg-slate-50 text-slate-500 hover:bg-slate-900 hover:text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all border border-slate-100 shadow-sm">
                                                    Cancel Session
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Student Feedback -->
        <section class="mt-12">
            <div class="flex items-center justify-between mb-8 px-4">
                <h3 class="text-xl font-extrabold text-slate-900">Recent Student Feedback</h3>
            </div>
            
            <?php if (empty($reviews)): ?>
                <div class="bg-white p-12 rounded-[2.5rem] border border-slate-50 text-center shadow-sm">
                    <p class="text-slate-400 font-bold">No reviews yet. Complete sessions to see what students think!</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach (array_slice($reviews, 0, 4) as $review): ?>
                    <div class="bg-white p-8 rounded-[2rem] border border-slate-50 shadow-sm hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-xs">
                                    <?= strtoupper(substr($review['student_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold text-slate-900"><?= htmlspecialchars($review['student_name']) ?></h4>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?= date('M d, Y', strtotime($review['start_time'])) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <svg class="w-3 h-3 <?= $i <= $review['rating'] ? 'text-amber-400' : 'text-slate-200' ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <?php if ($review['comment']): ?>
                            <p class="text-sm text-slate-600 font-medium leading-relaxed italic">"<?= htmlspecialchars($review['comment']) ?>"</p>
                        <?php else: ?>
                            <p class="text-sm text-slate-400 font-medium italic">No comment provided.</p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        <?php else: ?>
        <!-- Rejection Info -->
        <div class="bg-white rounded-[2.5rem] p-20 text-center border border-slate-50 shadow-sm">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-8">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900 mb-4">Access Restricted</h3>
            <p class="text-slate-500 font-medium max-w-md mx-auto leading-relaxed">
                We're sorry, but your tutor application has been rejected by the administrator. This may be due to verification issues or missing requirements. Please contact <a href="mailto:admin@skillswap.com" class="font-bold underline">admin@skillswap.com</a> for more information.
            </p>
        </div>
        <?php endif; ?>
    </main>

    <!-- Update Availability Modal -->
    <div id="availabilityModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden animate-in fade-in duration-300">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden">
            <button onclick="toggleAvailabilityModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="text-center mb-10">
                <h3 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Set Your Hours</h3>
                <p class="text-slate-500 font-medium">Update your weekly teaching schedule.</p>
            </div>

            <form action="../../api/updateAvailability.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 ml-1">Day of Week</label>
                    <select name="day" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none ring-1 ring-slate-100 focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all outline-none font-medium text-slate-700 appearance-none">
                        <option value="Mon">Monday</option>
                        <option value="Tue">Tuesday</option>
                        <option value="Wed">Wednesday</option>
                        <option value="Thu">Thursday</option>
                        <option value="Fri">Friday</option>
                        <option value="Sat">Saturday</option>
                        <option value="Sun">Sunday</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 ml-1">Start Time</label>
                        <input type="time" name="start_time" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none ring-1 ring-slate-100 focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all outline-none font-medium text-slate-700">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 ml-1">End Time</label>
                        <input type="time" name="end_time" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none ring-1 ring-slate-100 focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all outline-none font-medium text-slate-700">
                    </div>
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-5 rounded-2xl shadow-xl shadow-indigo-100 transition-all active:scale-95 mt-4">
                    Update Availability
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleAvailabilityModal() {
            const modal = document.getElementById('availabilityModal');
            modal.classList.toggle('hidden');
            if (!modal.classList.contains('hidden')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal on background click
        window.onclick = function(event) {
            const modal = document.getElementById('availabilityModal');
            if (event.target == modal) toggleAvailabilityModal();
        }
    </script>
    <!-- Custom Confirmation Modal -->
    <div id="confirm-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-6 backdrop-blur-md bg-slate-900/40 animate-in fade-in duration-300">
        <div class="bg-white/80 backdrop-blur-2xl border border-white rounded-[2.5rem] shadow-2xl shadow-indigo-500/10 max-w-md w-full p-10 text-center animate-in zoom-in-95 duration-300">
            <div id="modal-icon-container" class="w-20 h-20 rounded-3xl mx-auto mb-8 flex items-center justify-center">
                <svg id="modal-icon" class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
            </div>
            <h3 id="modal-title" class="text-2xl font-extrabold text-slate-900 mb-4 tracking-tight">Are you sure?</h3>
            <p id="modal-description" class="text-slate-500 font-medium mb-10 leading-relaxed"></p>
            
            <div class="flex items-center gap-4">
                <button type="button" onclick="closeModal()" class="flex-1 px-8 py-4 rounded-2xl bg-white border border-slate-100 text-slate-400 font-black hover:bg-slate-50 transition-all active:scale-95">
                    Cancel
                </button>
                <button type="button" id="modal-confirm-btn" class="flex-1 px-8 py-4 rounded-2xl text-white font-black shadow-lg transition-all active:scale-95">
                    Confirm
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentForm = null;

        const modalConfig = {
            'confirm': {
                title: 'Confirm Session',
                desc: 'This will lock in the session with the student. They will be notified immediately.',
                color: 'bg-emerald-50 text-emerald-600',
                btnColor: 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-200',
                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>'
            },
            'decline': {
                title: 'Decline Request',
                desc: 'Are you sure you want to decline this booking request? The student will be notified.',
                color: 'bg-red-50 text-red-600',
                btnColor: 'bg-red-600 hover:bg-red-700 shadow-red-200',
                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>'
            },
            'cancel': {
                title: 'Cancel Session',
                desc: 'Cancelling a confirmed session may affect your reliability rating. Are you sure you want to proceed?',
                color: 'bg-slate-100 text-slate-600',
                btnColor: 'bg-slate-900 hover:bg-black shadow-slate-200',
                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>'
            }
        };

        function showConfirm(event, action) {
            event.preventDefault();
            currentForm = event.target;
            
            const config = modalConfig[action];
            const modal = document.getElementById('confirm-modal');
            const iconContainer = document.getElementById('modal-icon-container');
            const icon = document.getElementById('modal-icon');
            const title = document.getElementById('modal-title');
            const desc = document.getElementById('modal-description');
            const confirmBtn = document.getElementById('modal-confirm-btn');

            // Set content
            title.textContent = config.title;
            desc.textContent = config.desc;
            icon.innerHTML = config.icon;
            
            // Set styles
            iconContainer.className = `w-20 h-20 rounded-3xl mx-auto mb-8 flex items-center justify-center ${config.color}`;
            confirmBtn.className = `flex-1 px-8 py-4 rounded-2xl text-white font-black shadow-lg transition-all active:scale-95 ${config.btnColor}`;
            
            // Show modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Handle confirm
            confirmBtn.onclick = () => {
                if(currentForm) currentForm.submit();
            };
        }

        function closeModal() {
            const modal = document.getElementById('confirm-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            currentForm = null;
        }

        // Close on ESC
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</body>
</html>
