<?php
/**
 * Learner Booking Page
 * Refactored to use Tutor Availability from the database.
 */

session_start();
require_once '../../functions/db.php';
require_once '../../functions/authFunctions.php';
require_once '../../functions/learnerFunctions.php';

// Route protection
if (!requireAuth('learner')) {
    header('Location: ../auth.php');
    exit();
}

$tutorId = $_GET['tutor_id'] ?? null;


if (!$tutorId) {
    header('Location: dashboard.php');
    exit();
}

// Fetch tutor availability using model function
$tutor = getTutorDetails($tutorId);
$availability = getTutorAvailability($tutorId);

$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Session with <?= htmlspecialchars($tutor['name'] ?? 'Tutor') ?> - SkillSwap</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        html { font-size: 11px !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F3F4F6; font-size: 1rem !important; line-height: 1.6 !important; }
        .gradient-btn { background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%); }
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-8">

    <!-- Mock Modal Container -->
    <div class="max-w-4xl w-full bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] overflow-hidden border border-slate-100 flex flex-col">
        
        <!-- Header -->
        <div class="p-8 sm:p-10 flex items-center justify-between border-b border-slate-50 relative">
            <div class="flex items-center gap-6">
                <!-- Avatar -->
                <div class="relative">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?= urlencode($tutor['name'] ?? 'Sarah') ?>" 
                         class="w-20 h-20 rounded-full bg-indigo-50 border-4 border-white shadow-sm" alt="Tutor">
                    <div class="absolute -bottom-1 -right-1 bg-amber-400 text-white text-[10px] font-black px-2 py-0.5 rounded-full flex items-center gap-0.5 border-2 border-white">
                        <svg class="w-2 h-2 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        4.9
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Book Session with <?= htmlspecialchars($tutor['name'] ?? 'Tutor') ?></h1>
                    <p class="text-indigo-600 font-bold text-sm tracking-wide">
                        Expert Tutor <span class="text-slate-300 mx-2">•</span> <span class="text-slate-500"><?= htmlspecialchars($tutor['primary_subject'] ?? 'Professional Coaching') ?></span>
                    </p>
                </div>
            </div>
            <a href="dashboard.php" class="text-slate-300 hover:text-slate-600 transition p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
        </div>

        <!-- Main Content Grid -->
        <div class="flex flex-col md:flex-row">
            
            <!-- Left: Decorative Calendar -->
            <div class="flex-1 p-8 sm:p-10 border-r border-slate-50">
                <div class="flex items-center justify-between mb-8 px-2">
                    <button class="text-slate-400 hover:text-indigo-600 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                    <h2 class="text-lg font-[800] text-slate-900 tracking-tight">January 2026</h2>
                    <button class="text-slate-400 hover:text-indigo-600 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                </div>

                <div class="calendar-grid text-center text-xs font-black text-slate-300 mb-6 uppercase tracking-widest">
                    <span>S</span><span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span>
                </div>
                
                <div class="calendar-grid text-center gap-y-4">
                    <!-- Sample Days (Decorative) -->
                    <?php for($i=1; $i<=25; $i++): ?>
                        <div class="relative py-2">
                            <span class="<?= $i == 24 ? 'bg-indigo-600 text-white w-10 h-10 flex items-center justify-center rounded-full mx-auto shadow-lg shadow-indigo-100 font-bold' : 'text-slate-700 font-bold' ?> cursor-default">
                                <?= $i ?>
                            </span>
                        </div>
                    <?php endfor; ?>
                    <div class="text-slate-200 font-bold py-2">26</div>
                    <div class="text-slate-200 font-bold py-2">27</div>
                    <div class="text-slate-200 font-bold py-2">28</div>
                </div>
            </div>

            <!-- Right: Booking Selection -->
            <div class="flex-1 p-8 sm:p-10 flex flex-col justify-between">
                <form action="../../api/bookSession.php" method="POST" id="bookingForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="tutor_id" value="<?= htmlspecialchars($tutorId) ?>">
                    
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h2 class="text-lg font-[800] text-slate-900">Available Time Slots</h2>
                        </div>

                        <?php if ($error): ?>
                            <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-2xl flex items-center gap-3 border border-red-100 animate-pulse text-xs font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="mb-6 p-4 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center gap-3 border border-emerald-100 text-xs font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <?= htmlspecialchars($success) ?>
                            </div>
                        <?php endif; ?>

                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block ml-1">Select A slot</label>
                            <div class="relative group">
                                <select name="date" required <?= empty($availability) ? 'disabled' : '' ?>
                                        class="w-full px-6 py-5 rounded-2xl bg-white border-2 border-slate-100 focus:border-indigo-600 focus:ring-0 transition-all outline-none font-bold text-slate-700 appearance-none disabled:bg-slate-50 disabled:cursor-not-allowed">
                                    <option value="">Select your timing</option>
                                    <?php foreach ($availability as $slot): 
                                        $startTimeDisplay = date("h:i A", strtotime($slot['start_time']));
                                        $endTimeDisplay = date("h:i A", strtotime($slot['end_time']));
                                        $value = $slot['day_of_week'] . " " . $slot['start_time'];
                                    ?>
                                        <option value="<?= htmlspecialchars($value) ?>">
                                            <?= htmlspecialchars($slot['day_of_week']) ?> • <?= $startTimeDisplay ?> - <?= $endTimeDisplay ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <?php if (empty($availability)): ?>
                            <p class="mt-4 text-xs font-bold text-orange-500 flex items-center gap-2 px-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                This tutor has no available slots yet.
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Session Info Box -->
                    <div class="bg-indigo-50/50 rounded-2xl p-6 border border-indigo-100/50">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-indigo-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <h3 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-1">Session Info</h3>
                                <p class="text-[13px] font-medium text-slate-600 leading-relaxed">
                                    You are booking a <span class="font-black text-indigo-700">60 minute</span> session. All times are displayed in the tutor's time zone: <span class="font-black"><?= htmlspecialchars($tutor['timezone'] ?? 'UTC') ?></span>.
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-8 sm:p-10 bg-white border-t border-slate-50 flex flex-col sm:flex-row items-center justify-between gap-8 mt-auto">
            <div class="flex-1 w-full space-y-2">
                <div class="flex justify-between text-sm font-bold">
                    <span class="text-slate-400">Session Price</span>
                    <span class="text-slate-900">$<?= number_format($tutor['hourly_rate'] ?? 15, 2) ?></span>
                </div>
                <div class="flex justify-between text-sm font-bold">
                    <span class="text-slate-400">Commission <span class="bg-slate-100 text-[10px] px-2 py-0.5 rounded-md ml-1 font-black">10%</span></span>
                    <span class="text-amber-600">$<?= number_format(($tutor['hourly_rate'] ?? 15) * 0.1, 2) ?></span>
                </div>
                <div class="flex justify-between pt-2 border-t border-slate-50">
                    <span class="text-lg font-extrabold text-slate-900">Total</span>
                    <span class="text-lg font-extrabold text-indigo-600">$<?= number_format(($tutor['hourly_rate'] ?? 15) * 1.1, 2) ?></span>
                </div>
            </div>
            
            <button type="submit" form="bookingForm" <?= empty($availability) ? 'disabled' : '' ?>
                    class="gradient-btn w-full sm:w-auto text-white px-10 py-5 rounded-2xl text-base font-black shadow-2xl shadow-indigo-200 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-3">
                Confirm Booking
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>
    </div>

</body>
</html>
