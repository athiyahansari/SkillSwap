<?php
/**
 * Tutor Earnings Dashboard
 * High-fidelity implementation based on design reference.
 */

session_start();

require_once '../../functions/db.php';
require_once '../../functions/authFunctions.php';
require_once '../../functions/tutorFunctions.php';

// Security check: Ensure user is logged in and is a tutor
if (!requireAuth('tutor')) {
    header('Location: ../auth.php');
    exit();
}

$userId = $_SESSION['user_id'];
$profile = getTutorProfileByUserId($userId);

if (!$profile) {
    die("Tutor profile not found.");
}

$profileId = $profile['profile_id'];
$isVerified = ($profile['status'] === 'verified');

// Fetch finance data if verified
$financeSummary = $isVerified ? getTutorFinanceSummary($userId) : null;
$recentEarnings = $isVerified ? getTutorRecentEarnings($userId, 10) : [];

$displayName = $profile['name'];
$subjectName = $profile['subject_name'] ?? 'Tutor';

// Derived values
$totalEarned = $financeSummary['total_earnings'] ?? 0;
$platformFees = $financeSummary['total_platform_fee'] ?? 0;
$pendingClearance = 50.00; // Static placeholder as per mockup requirement or simple derivation
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earnings Overview - SkillSwap</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        html { font-size: 11px !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F9FD; font-size: 1rem !important; line-height: 1.6 !important; }
        .sidebar-item-active { background-color: #EFEEFE; color: #6366F1; }
        .sidebar-item-active svg { color: #6366F1; }
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
            <a href="dashboard.php" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-slate-500 hover:bg-slate-50 transition-all group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Home
            </a>
            <a href="earnings.php" class="sidebar-item-active flex items-center gap-4 px-6 py-4 rounded-2xl font-bold transition-all group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
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
        <!-- Top Nav -->
        <header class="flex items-center justify-between mb-12">
            <div>
                <h1 class="text-4xl font-extrabold text-slate-900 mb-2 tracking-tight">Earnings Overview</h1>
                <p class="text-slate-400 font-medium">Track your financial growth on SkillSwap.</p>
            </div>
            <a href="dashboard.php" class="bg-white border border-slate-100 text-slate-600 px-6 py-3 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-slate-50 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
        </header>

        <?php if ($profile['status'] === 'rejected'): ?>
            <!-- Rejection Warning -->
            <div class="bg-red-50 border border-red-100 rounded-[2rem] p-12 text-center animate-in zoom-in-95 duration-500">
                <div class="w-20 h-20 bg-red-100 text-red-600 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <h3 class="text-2xl font-extrabold text-red-900 mb-2">Application Rejected</h3>
                <p class="text-red-700/70 font-medium mb-8 max-w-md mx-auto">Admin has explicitly rejected your application. Please contact <a href="mailto:admin@skillswap.com" class="font-bold underline">admin@skillswap.com</a> for more information regarding your status.</p>
                <div class="inline-flex items-center gap-2 bg-slate-900 text-white px-8 py-4 rounded-2xl font-black shadow-lg">
                    Access Restricted
                </div>
            </div>
        <?php elseif ($profile['status'] === 'pending'): ?>
            <!-- Verification Warning -->
            <div class="bg-amber-50 border border-amber-100 rounded-[2rem] p-12 text-center animate-in zoom-in-95 duration-500">
                <div class="w-20 h-20 bg-amber-100 text-amber-600 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-extrabold text-amber-900 mb-2">Verification Required</h3>
                <p class="text-amber-700/70 font-medium mb-8">Finance data is available after verification. Please complete your profile if you haven't already.</p>
                <a href="profile.php" class="inline-flex items-center gap-2 bg-amber-600 text-white px-8 py-4 rounded-2xl font-black shadow-lg shadow-amber-200 hover:bg-amber-700 transition-all">
                    Complete Profile
                </a>
            </div>
        <?php else: ?>
            <!-- Earnings Stats -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <!-- Total Earned -->
                <div class="bg-white p-8 rounded-[2rem] border border-slate-50 shadow-sm stats-card flex flex-col relative overflow-hidden">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Total Earned</p>
                    </div>
                    <div class="flex items-baseline gap-4 mb-2">
                        <h3 class="text-4xl font-black text-slate-900">$<?= number_format($totalEarned, 2) ?></h3>
                        <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-[10px] font-black flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path></svg>
                            12%
                        </span>
                    </div>
                    <p class="text-xs font-bold text-slate-400">vs. last month</p>
                    <!-- Decorative Icon -->
                    <div class="absolute -right-4 -bottom-4 w-32 h-32 text-slate-50 opacity-10">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <!-- Pending Clearance -->
                <div class="bg-white p-8 rounded-[2rem] border border-slate-50 shadow-sm stats-card flex flex-col relative overflow-hidden">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Pending Clearance</p>
                    </div>
                    <div class="flex items-center gap-4 mb-2">
                        <h3 class="text-4xl font-black text-slate-900">$<?= number_format($pendingClearance, 2) ?></h3>
                        <span class="bg-orange-50 text-orange-500 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider">Processing</span>
                    </div>
                    <p class="text-xs font-bold text-slate-400">Est. clearance: 2 days</p>
                    <!-- Decorative Icon -->
                    <div class="absolute -right-4 -bottom-4 w-32 h-32 text-slate-50 opacity-10">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <!-- Platform Fees -->
                <div class="bg-white p-8 rounded-[2rem] border border-slate-50 shadow-sm stats-card flex flex-col relative overflow-hidden">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-slate-50 text-slate-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Platform Fees Paid</p>
                    </div>
                    <div class="flex items-baseline gap-4 mb-2">
                        <h3 class="text-4xl font-black text-slate-900">$<?= number_format($platformFees, 2) ?></h3>
                    </div>
                    <p class="text-xs font-bold text-slate-400">10% service fee applied</p>
                    <!-- Decorative Icon -->
                    <div class="absolute -right-4 -bottom-4 w-32 h-32 text-slate-50 opacity-10">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>
            </section>

            <!-- Recent Payouts -->
            <section class="bg-white rounded-[2.5rem] border border-slate-50 shadow-sm overflow-hidden">
                <div class="px-10 py-8 flex items-center justify-between border-b border-slate-50">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-900">Recent Payouts</h3>
                    </div>
                    <a href="#" class="text-indigo-600 text-sm font-bold hover:underline flex items-center gap-2">
                        View All
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-10 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                                <th class="px-10 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Student</th>
                                <th class="px-10 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Gross Amount</th>
                                <th class="px-10 py-4 text-[10px] font-black text-indigo-400 uppercase tracking-widest">Platform Fee</th>
                                <th class="px-10 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Net Earning</th>
                                <th class="px-10 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php if (empty($recentEarnings)): ?>
                            <tr>
                                <td colspan="6" class="px-10 py-20 text-center text-slate-400 font-medium italic">No recent payouts to display.</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($recentEarnings as $entry): ?>
                                <tr class="hover:bg-slate-50/50 transition-all group">
                                    <td class="px-10 py-6 text-sm font-bold text-slate-800">
                                        <?= date('M d, Y', strtotime($entry['session_date'])) ?>
                                        <p class="text-[10px] font-black text-slate-400 tracking-widest mt-1 uppercase">ID: <?= $entry['payment_id'] ?></p>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="flex items-center gap-3">
                                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?= urlencode($entry['student_name']) ?>" class="w-8 h-8 rounded-full bg-slate-100">
                                            <span class="text-sm font-extrabold text-slate-800"><?= htmlspecialchars($entry['student_name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6 text-sm font-bold text-slate-600">
                                        $<?= number_format($entry['total_amount'], 2) ?>
                                    </td>
                                    <td class="px-10 py-6 text-sm font-bold text-indigo-500">
                                        -$<?= number_format($entry['platform_fee'], 2) ?>
                                    </td>
                                    <td class="px-10 py-6 text-sm font-black text-slate-900">
                                        $<?= number_format($entry['tutor_earnings'], 2) ?>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="flex justify-center">
                                            <?php 
                                            $status = strtolower($entry['payment_status'] ?? 'paid');
                                            $statusClass = 'bg-slate-100 text-slate-700 ring-slate-200';
                                            $dotClass = 'bg-slate-500';

                                            if ($status === 'paid' || $status === 'completed') {
                                                $statusClass = 'bg-emerald-100 text-emerald-800 ring-emerald-200';
                                                $dotClass = 'bg-emerald-600';
                                            } elseif ($status === 'pending' || $status === 'processing') {
                                                $statusClass = 'bg-amber-100 text-amber-800 ring-amber-200';
                                                $dotClass = 'bg-amber-600';
                                            }
                                            ?>
                                            <span class="<?= $statusClass ?> px-5 py-2.5 rounded-2xl text-[12px] font-extrabold uppercase tracking-widest flex items-center gap-2.5 ring-1 shadow-sm">
                                                <span class="w-2.5 h-2.5 <?= $dotClass ?> rounded-full shadow-sm"></span>
                                                <?= ucfirst($status) ?>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php endif; ?>
    </main>

</body>
</html>
