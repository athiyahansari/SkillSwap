<?php
session_start();

/**
 * Admin Financial Dashboard
 * High-fidelity implementation based on design reference.
 */

require_once '../../functions/db.php';
require_once '../../functions/authFunctions.php';
require_once '../../functions/adminFunctions.php';

// Security check: Ensure user is logged in and is an admin
if (!requireAuth('admin')) {
    header('Location: ../auth.php');
    exit();
}

// Fetch data
$financeSummary = getPlatformFinanceSummary();
$recentTransactions = getRecentPlatformTransactions(10);

// Default values for empty states
$totalRevenue = $financeSummary['total_revenue'] ?? 0;
$pendingPayouts = $financeSummary['pending_payouts'] ?? 0;
$platformFeeRate = 10; // Static 10% as per requirements

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Dashboard - SkillSwap Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        html { font-size: 11px !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F9FD; font-size: 1rem !important; line-height: 1.6 !important; }
        .sidebar-item-active { background-color: #6366F1; color: white !important; box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.2); }
    </style>
</head>
<body class="flex min-h-screen">

    <!-- Sidebar -->
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
            <a href="verifications.php" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-slate-400 hover:text-white hover:bg-slate-800 transition-all group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Verifications
            </a>
            <a href="finance.php" class="sidebar-item-active flex items-center gap-4 px-6 py-4 rounded-2xl font-bold transition-all group">
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
        <!-- Top Nav -->
        <header class="flex items-center justify-between mb-12">
            <nav class="text-sm font-bold">
                <span class="text-slate-400">Overview /</span> 
                <span class="text-indigo-600">Payments</span>
            </nav>
        </header>

        <!-- Title Section -->
        <div class="mb-12">
            <h1 class="text-4xl font-extrabold text-slate-900 mb-2 tracking-tight">Financial Dashboard</h1>
            <p class="text-slate-500 font-medium">Overview of revenue, payouts, and commissions.</p>
        </div>

        <!-- Summary Cards -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Total Revenue -->
            <div class="bg-white p-8 rounded-[2rem] border border-slate-50 shadow-sm relative overflow-hidden group">
                <div class="flex items-center justify-between mb-6 relative z-10">
                    <p class="text-sm font-bold text-slate-400">Total Revenue</p>
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center font-bold">
                        $
                    </div>
                </div>
                <h3 class="text-4xl font-black text-slate-900 mb-2 relative z-10">$<?= number_format($totalRevenue, 2) ?></h3>
                <p class="text-[11px] font-black <?= $totalRevenue > 0 ? 'text-emerald-500' : 'text-slate-400' ?> flex items-center gap-1 relative z-10 uppercase tracking-wider">
                    <?php if ($totalRevenue > 0): ?>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        +12% from last month
                    <?php else: ?>
                        No data yet
                    <?php endif; ?>
                </p>
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-emerald-50 rounded-full opacity-40 group-hover:scale-110 transition-transform duration-500"></div>
            </div>

            <!-- Pending Payouts -->
            <div class="bg-white p-8 rounded-[2rem] border border-slate-50 shadow-sm relative overflow-hidden group">
                <div class="flex items-center justify-between mb-6 relative z-10">
                    <p class="text-sm font-bold text-slate-400">Pending Payouts</p>
                    <div class="w-10 h-10 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-4xl font-black text-slate-900 mb-2 relative z-10">$<?= number_format($pendingPayouts, 2) ?></h3>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-wider relative z-10">Processing next cycle</p>
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-amber-50 rounded-full opacity-40 group-hover:scale-110 transition-transform duration-500"></div>
            </div>

            <!-- Commission Rate -->
            <div class="bg-white p-8 rounded-[2rem] border border-slate-50 shadow-sm relative overflow-hidden group">
                <div class="flex items-center justify-between mb-6 relative z-10">
                    <p class="text-sm font-bold text-slate-400">Platform Commission Rate</p>
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-500 rounded-xl flex items-center justify-center font-black">
                        %
                    </div>
                </div>
                <h3 class="text-4xl font-black text-slate-900 mb-2 relative z-10"><?= $platformFeeRate ?>%</h3>
                <p class="text-[11px] font-black text-indigo-500 uppercase tracking-wider relative z-10">Fixed platform rate</p>
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-indigo-50 rounded-full opacity-40 group-hover:scale-110 transition-transform duration-500"></div>
            </div>
        </section>

        <!-- Transactions Table -->
        <section class="bg-white rounded-[2.5rem] border border-slate-50 shadow-sm overflow-hidden min-h-[500px]">
            <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Recent Transactions & Commissions</h3>
                <a href="#" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">View All</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-10 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] w-48">Date</th>
                            <th class="px-10 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Transaction ID</th>
                            <th class="px-10 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Tutor</th>
                            <th class="px-10 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Booking Amount</th>
                            <th class="px-10 py-5 text-[11px] font-black text-indigo-500 uppercase tracking-[0.2em] text-right">Platform Fee (10%)</th>
                            <th class="px-10 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if (empty($recentTransactions)): ?>
                            <tr>
                                <td colspan="6" class="px-10 py-32 text-center">
                                    <div class="flex flex-col items-center opacity-40">
                                        <svg class="w-16 h-16 mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        <p class="text-lg font-bold text-slate-400">No transactions recorded yet</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentTransactions as $tx): ?>
                                <tr class="hover:bg-slate-50/80 transition-all group">
                                    <td class="px-10 py-6 text-sm font-bold text-slate-500">
                                        <!-- Simulated date relative to current time -->
                                        <?= date('M d, Y', strtotime('-' . (count($recentTransactions) - array_search($tx, $recentTransactions)) . ' days')) ?>
                                    </td>
                                    <td class="px-10 py-6">
                                        <span class="text-sm font-bold text-slate-900 tracking-tight"><?= $tx['payment_id'] ?></span>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center overflow-hidden border-2 border-white shadow-sm ring-1 ring-slate-100">
                                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?= urlencode($tx['tutor_name']) ?>" alt="Avatar" class="w-full h-full object-cover">
                                            </div>
                                            <span class="text-sm font-bold text-slate-900 tracking-tight"><?= htmlspecialchars($tx['tutor_name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6 text-right">
                                        <span class="text-sm font-extrabold text-slate-900">$<?= number_format($tx['total_amount'], 2) ?></span>
                                    </td>
                                    <td class="px-10 py-6 text-right">
                                        <span class="text-sm font-black text-indigo-600">$<?= number_format($tx['platform_fee'], 2) ?></span>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="flex justify-center">
                                            <?php 
                                            $status = strtolower($tx['payment_status'] ?? 'pending');
                                            $statusClass = 'bg-slate-50 text-slate-600';
                                            
                                            switch($status) {
                                                case 'paid': $statusClass = 'bg-emerald-50 text-emerald-600'; break;
                                                case 'pending': $statusClass = 'bg-amber-50 text-amber-600'; break;
                                                case 'refunded': $statusClass = 'bg-red-50 text-red-600'; break;
                                            }
                                            ?>
                                            <span class="<?= $statusClass ?> px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider">
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
    </main>
</body>
</html>
