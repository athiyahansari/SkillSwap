<?php
session_start();

// Security check: Ensure user is logged in and is a learner
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'learner') {
    header('Location: ../auth.php');
    exit();
}

require_once '../../functions/db.php';
require_once '../../functions/learnerFunctions.php';
require_once '../../functions/flash.php';

$userId = $_SESSION['user_id'];
$profile = getLearnerProfile($userId);
$bookings = getLearnerBookings($userId);

$searchTerm = $_GET['q'] ?? null;
$tutors = getTutors($searchTerm);


$userName = explode(' ', $profile['name'] ?? 'Learner')[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SkillSwap</title>
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
            <a href="dashboard.php" class="sidebar-item-active flex items-center gap-4 px-6 py-4 rounded-2xl font-bold transition-all group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Find a Tutor
            </a>
            <a href="bookings.php" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-slate-500 hover:bg-slate-50 transition-all group">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
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
        <header class="mb-12">
            <h1 class="text-4xl font-[800] text-slate-900 mb-2">Find a Tutor</h1>
            <p class="text-slate-400 font-medium">Connect with expert tutors in any subject tailored to your goals.</p>
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

        <!-- Search and Filters -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-50 mb-12">
            <form action="dashboard.php" method="GET" class="relative mb-6">
                <svg class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="q" value="<?= htmlspecialchars($searchTerm ?? '') ?>" placeholder="Search by subject (e.g. Calculus, Physics)..." class="w-full pl-14 pr-8 py-5 rounded-2xl bg-slate-50/50 border border-slate-100 focus:border-indigo-500 focus:bg-white outline-none transition-all placeholder:text-slate-300 font-medium text-slate-700">
                <button type="submit" class="hidden">Search</button>
            </form>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 text-slate-400 text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                    Sort by: <span class="text-slate-600">Recommended</span>
                </div>
            </div>
        </div>

        <!-- Tutor Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($tutors as $tutor): ?>
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-50 hover:shadow-xl hover:shadow-indigo-50/50 hover:border-indigo-100 transition-all duration-300 group">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <img src="<?= $tutor['image'] ?? 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($tutor['name'] ?? 'Tutor') ?>" alt="Tutor" class="w-14 h-14 rounded-2xl bg-indigo-50 object-cover border border-indigo-50">
                        <div>
                            <h3 class="font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors"><?= htmlspecialchars($tutor['name'] ?? 'Tutor Name') ?></h3>
                            <p class="text-xs font-bold text-slate-400"><?= htmlspecialchars($tutor['timezone'] ?? 'Global') ?></p>
                        </div>
                    </div>
                    <div class="bg-amber-50 text-amber-500 px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 fill-amber-500" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <span class="text-xs font-black"><?= $tutor['avg_rating'] ? number_format($tutor['avg_rating'], 1) . ' (' . $tutor['review_count'] . ')' : 'New Tutor' ?></span>
                    </div>
                </div>

                <div class="mb-4 flex flex-wrap gap-2">
                    <?php 
                    $tutorSubjects = !empty($tutor['subjects']) ? array_map('trim', explode(',', $tutor['subjects'])) : [];
                    if (empty($tutorSubjects) && !empty($tutor['primary_subject'])) {
                        $tutorSubjects = [$tutor['primary_subject']];
                    }
                    foreach (array_slice($tutorSubjects, 0, 3) as $subject): 
                    ?>
                        <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider">
                            <?= htmlspecialchars($subject) ?>
                        </span>
                    <?php endforeach; ?>
                    <?php if (count($tutorSubjects) > 3): ?>
                        <span class="bg-slate-50 text-slate-400 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider">
                            +<?= count($tutorSubjects) - 3 ?>
                        </span>
                    <?php endif; ?>
                </div>

                <p class="text-sm font-medium text-slate-500 leading-relaxed mb-8 line-clamp-2">
                    <?= htmlspecialchars($tutor['bio'] ?? 'Expert tutor ready to help you reach your academic goals.') ?>
                </p>

                <div class="flex items-center justify-between border-t border-slate-50 pt-8">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Hourly Rate</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-black text-slate-900">$<?= number_format($tutor['hourly_rate'] ?? 45.00, 0) ?></span>
                            <span class="text-slate-400 text-sm font-bold">/hr</span>
                        </div>
                    </div>
                    <a href="book.php?tutor_id=<?= $tutor['profile_id'] ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3.5 rounded-2xl text-sm font-black shadow-lg shadow-indigo-100 transition-all active:scale-95">
                        Book Now
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
