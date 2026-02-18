<?php
session_start();

/**
 * Admin Tutor Verification Dashboard
 * High-fidelity implementation based on design reference.
 */

// 1. Security check: Ensure user is logged in and is an admin
// requireAuth('admin') is called here. Assuming authFunctions.php provides it.
require_once '../../functions/db.php';
require_once '../../functions/authFunctions.php';
require_once '../../functions/adminFunctions.php';
require_once '../../functions/flash.php';

if (!requireAuth('admin')) {
    header('Location: ../auth.php');
    exit();
}

// 2. Fetch data
$allTutors = getAllTutors();

// Calculate some stats for the cards
$totalPending = 0;
$totalVerified = 0;
$totalRejected = 0;

foreach ($allTutors as $tutor) {
    $currentStatus = strtolower(trim($tutor['status'] ?? ''));
    if ($currentStatus === 'pending') $totalPending++;
    elseif ($currentStatus === 'verified') $totalVerified++;
    elseif ($currentStatus === 'rejected') $totalRejected++;
}

// Mocking "New Today" for UI fidelity
$newToday = 5; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Verifications - SkillSwap Admin</title>
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

    <!-- Sidebar (Reusing layout pattern from tutor dashboard for consistency) -->
    <aside class="w-72 bg-[#1E293B] flex flex-col fixed h-full z-20">
        <div class="px-8 py-10 flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-white tracking-tight">SkillSwap</span>
                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Admin Panel</p>
            </div>
        </div>

        <nav class="flex-1 px-4 space-y-2">
            <div class="px-4 py-2 text-[11px] font-bold text-slate-500 uppercase tracking-widest opacity-50">Management</div>
            <a href="#" class="bg-indigo-600 flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-white transition-all shadow-lg shadow-indigo-900/20 group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Verifications
            </a>
            <a href="finance.php" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-slate-400 hover:text-white hover:bg-slate-800 transition-all group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Payments
            </a>
        </nav>

        <div class="px-6 py-10 border-t border-slate-800 mt-auto">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-slate-300 font-bold">A</div>
                    <div>
                        <h4 class="text-sm font-extrabold text-white">Admin User</h4>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Master Admin</p>
                    </div>
                </div>
            </div>
            <a href="../../api/logout.php" class="flex items-center gap-3 px-6 py-3 rounded-xl bg-slate-800/50 text-red-400 hover:bg-red-500/10 hover:text-red-500 transition-all font-bold text-xs group">
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
                <span class="text-slate-400">Home / Management /</span> 
                <span class="text-indigo-600">Verifications</span>
            </nav>
        </header>

        <!-- Page Title & Flash Messages -->
        <div class="mb-12">
            <h1 class="text-4xl font-extrabold text-slate-900 mb-2">Tutor Management & Verifications</h1>
            <p class="text-slate-500 font-medium">Review and manage tutor profiles and verification statuses.</p>

            <!-- Flash Notifications -->
            <?php $flash = getFlash(); ?>
            <?php if ($flash): ?>
                <div class="mt-6 p-6 rounded-[2rem] border animate-in slide-in-from-top-4 duration-300 flex items-center gap-4 shadow-sm
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
                    <span class="font-bold text-sm"><?= htmlspecialchars($flash['message']) ?></span>
                </div>
            <?php endif; ?>

            <!-- Flash Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="mt-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 animate-in slide-in-from-top-4 duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-bold text-sm"><?= $_SESSION['success'] ?></span>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mt-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl flex items-center gap-3 animate-in slide-in-from-top-4 duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <span class="font-bold text-sm"><?= $_SESSION['error'] ?></span>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        </div>

        <!-- Stats Cards -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white p-8 rounded-[2rem] border border-slate-50 shadow-sm flex flex-col relative overflow-hidden group">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-[10px] font-black flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path></svg>
                        2%
                    </span>
                </div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Pending</p>
                <h3 class="text-3xl font-black text-slate-900"><?= $totalPending ?></h3>
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-50 rounded-full opacity-20 group-hover:scale-110 transition-transform"></div>
            </div>

            <div class="bg-white p-8 rounded-[2rem] border border-slate-50 shadow-sm flex flex-col relative overflow-hidden group">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                </div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Verified</p>
                <h3 class="text-3xl font-black text-slate-900"><?= $totalVerified ?></h3>
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-50 rounded-full opacity-20 group-hover:scale-110 transition-transform"></div>
            </div>

            <div class="bg-white p-8 rounded-[2rem] border border-slate-50 shadow-sm flex flex-col relative overflow-hidden group">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Rejected</p>
                <h3 class="text-3xl font-black text-slate-900"><?= $totalRejected ?></h3>
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-red-50 rounded-full opacity-20 group-hover:scale-110 transition-transform"></div>
            </div>
        </section>

        <!-- Verification Queue Table -->
        <section class="bg-white rounded-[2.5rem] border border-slate-50 shadow-sm overflow-hidden mb-12">
            <div class="px-10 py-8 flex items-center justify-between border-b border-slate-50">
                <h3 class="text-xl font-extrabold text-slate-900">Verification Queue</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-10 py-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Tutor Name</th>
                            <th class="px-10 py-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Qualification</th>
                            <th class="px-10 py-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Email</th>
                            <th class="px-10 py-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Hourly Rate</th>
                            <th class="px-10 py-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Transcript</th>
                            <th class="px-10 py-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                            <th class="px-10 py-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if (empty($allTutors)): ?>
                        <tr>
                            <td colspan="6" class="px-10 py-20 text-center text-slate-400 font-medium">No tutors found in the system.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($allTutors as $tutor): ?>
                            <tr class="hover:bg-slate-50/80 transition-all group">
                                <td class="px-10 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500 font-black text-sm">
                                            <?= strtoupper(substr($tutor['name'], 0, 1)) ?>
                                        </div>
                                        <span class="font-extrabold text-slate-900"><?= htmlspecialchars($tutor['name']) ?></span>
                                    </div>
                                </td>
                                <td class="px-10 py-6">
                                    <div class="flex flex-col">
                                        <span class="font-extrabold text-slate-900"><?= htmlspecialchars($tutor['qualification'] ?? 'N/A') ?></span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?= htmlspecialchars($tutor['university_name'] ?? 'Self-taught') ?></span>
                                    </div>
                                </td>
                                <td class="px-10 py-6">
                                    <span class="font-medium text-slate-500"><?= htmlspecialchars($tutor['email']) ?></span>
                                </td>
                                <td class="px-10 py-6">
                                    <span class="font-bold text-slate-900">$<?= number_format($tutor['hourly_rate'], 2) ?>/hr</span>
                                </td>
                                <td class="px-10 py-6">
                                    <?php if ($tutor['transcript_provided']): ?>
                                        <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            Provided
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs font-bold text-slate-400 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Not Provided
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-10 py-6">
                                    <?php 
                                        $displayStatus = strtolower(trim($tutor['status'] ?? ''));
                                        if ($displayStatus === 'pending'): 
                                    ?>
                                        <span class="bg-amber-50 text-amber-500 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center w-fit gap-2">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                            Pending
                                        </span>
                                    <?php elseif ($displayStatus === 'verified'): ?>
                                        <span class="bg-emerald-50 text-emerald-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center w-fit gap-2">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                            Verified
                                        </span>
                                    <?php elseif ($displayStatus === 'rejected'): ?>
                                        <span class="bg-red-50 text-red-500 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center w-fit gap-2">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                            Rejected
                                        </span>
                                    <?php else: ?>
                                        <span class="bg-slate-50 text-slate-500 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center w-fit gap-2">
                                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                                            <?= htmlspecialchars($tutor['status'] ?: 'Unknown') ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-10 py-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <?php if ($displayStatus === 'pending'): ?>
                                            <!-- Approve Form -->
                                            <form action="../../api/verifyTutor.php" method="POST" onsubmit="showConfirm(event, 'verify')">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="profile_id" value="<?= $tutor['profile_id'] ?>">
                                                <input type="hidden" name="action" value="verify">
                                                <button type="submit" title="Verify Tutor" class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all flex items-center justify-center shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                            <!-- Reject Form -->
                                            <form action="../../api/verifyTutor.php" method="POST" onsubmit="showConfirm(event, 'reject')">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="profile_id" value="<?= $tutor['profile_id'] ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" title="Reject Tutor" class="w-10 h-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>
                                        <?php elseif ($displayStatus === 'verified'): ?>
                                            <!-- Revoke Form -->
                                            <form action="../../api/verifyTutor.php" method="POST" onsubmit="showConfirm(event, 'reset')">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="profile_id" value="<?= $tutor['profile_id'] ?>">
                                                <input type="hidden" name="action" value="reset">
                                                <button type="submit" class="px-4 h-10 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center shadow-sm text-xs font-bold uppercase">
                                                    Revoke
                                                </button>
                                            </form>
                                        <?php elseif ($displayStatus === 'rejected'): ?>
                                            <!-- Re-approve Action -->
                                            <form action="../../api/verifyTutor.php" method="POST" onsubmit="showConfirm(event, 'verify')">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="profile_id" value="<?= $tutor['profile_id'] ?>">
                                                <input type="hidden" name="action" value="verify">
                                                <button type="submit" title="Approve Tutor" class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all flex items-center justify-center shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                            <!-- Return to Pending -->
                                            <form action="../../api/verifyTutor.php" method="POST" onsubmit="showConfirm(event, 'reset')">
                                                <input type="hidden" name="profile_id" value="<?= $tutor['profile_id'] ?>">
                                                <input type="hidden" name="action" value="reset">
                                                <button type="submit" class="px-4 h-10 rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-800 hover:text-white transition-all flex items-center justify-center shadow-sm text-xs font-bold uppercase">
                                                    Reset
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
            
            <div class="px-10 py-6 bg-slate-50/50 border-t border-slate-50 flex items-center justify-between">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Showing <?= count($allTutors ?: []) ?> total tutors</p>
            </div>
        </section>
    </main>
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
            'verify': {
                title: 'Verify Tutor',
                desc: 'This will grant the tutor full access to the platform and notify them of their approval.',
                color: 'bg-emerald-50 text-emerald-600',
                btnColor: 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-200',
                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>'
            },
            'reject': {
                title: 'Reject Profile',
                desc: 'This will deny the tutor access. They will be notified and asked to contact support.',
                color: 'bg-red-50 text-red-600',
                btnColor: 'bg-red-600 hover:bg-red-700 shadow-red-200',
                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>'
            },
            'reset': {
                title: 'Reset Status',
                desc: 'This will move the tutor back to the pending queue for re-evaluation.',
                color: 'bg-indigo-50 text-indigo-600',
                btnColor: 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200',
                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>'
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
