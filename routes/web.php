<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LearnerDashboardController;
use App\Http\Controllers\TutorDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\TutorProfileController;
use App\Http\Controllers\TutorSubjectController;
use App\Http\Controllers\PublicTutorController;
use App\Http\Controllers\SocialAuthController;

Route::get('/tutors', [PublicTutorController::class, 'index'])->name('tutors.index');
Route::get('/tutors/{tutorProfile}', [PublicTutorController::class, 'show'])->name('tutors.show');
Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handle'])->name('stripe.webhook');

// Google OAuth Routes
Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])
    ->middleware('guest')
    ->name('auth.google.redirect');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])
    ->name('auth.google.callback');

Route::middleware(['auth', 'role:learner'])->group(function () {
    Route::get('/learner/dashboard', [LearnerDashboardController::class, 'index'])->name('learner.dashboard');
    Route::get('/learner/bookings', [\App\Http\Controllers\LearnerBookingController::class, 'index'])->name('learner.bookings.index');

    // Sensitive actions require email verification
    Route::middleware('verified')->group(function () {
        Route::post('/tutors/{tutorProfile}/book', [\App\Http\Controllers\LearnerBookingController::class, 'store'])->name('learner.bookings.store');
        Route::put('/learner/bookings/{booking}/cancel', [\App\Http\Controllers\LearnerBookingController::class, 'cancel'])->name('learner.bookings.cancel');
        Route::get('/learner/bookings/{booking}/review', [\App\Http\Controllers\ReviewController::class, 'create'])->name('learner.reviews.create');
        Route::post('/learner/bookings/{booking}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('learner.reviews.store');
    });
});

Route::middleware(['auth', 'role:tutor'])->group(function () {
    Route::get('/tutor/dashboard', [TutorDashboardController::class, 'index'])->name('tutor.dashboard');
    Route::get('/tutor/profile', [TutorProfileController::class, 'show'])->name('tutor.profile.show');
    Route::get('/tutor/profile/create', [TutorProfileController::class, 'create'])->name('tutor.profile.create');
    Route::post('/tutor/profile', [TutorProfileController::class, 'store'])->name('tutor.profile.store');
    Route::get('/tutor/profile/edit', [TutorProfileController::class, 'edit'])->name('tutor.profile.edit');
    Route::put('/tutor/profile', [TutorProfileController::class, 'update'])->name('tutor.profile.update');
    
    Route::get('/tutor/subjects', [TutorSubjectController::class, 'edit'])->name('tutor.subjects.edit');
    Route::put('/tutor/subjects', [TutorSubjectController::class, 'update'])->name('tutor.subjects.update');

    Route::get('/tutor/bookings', [\App\Http\Controllers\TutorBookingController::class, 'index'])->name('tutor.bookings.index');
    Route::put('/tutor/bookings/{booking}/accept', [\App\Http\Controllers\TutorBookingController::class, 'accept'])->name('tutor.bookings.accept');
    Route::put('/tutor/bookings/{booking}/reject', [\App\Http\Controllers\TutorBookingController::class, 'reject'])->name('tutor.bookings.reject');
    Route::put('/tutor/bookings/{booking}/complete', [\App\Http\Controllers\TutorBookingController::class, 'complete'])->name('tutor.bookings.complete');

    Route::get('/tutor/earnings', [\App\Http\Controllers\TutorEarningsController::class, 'index'])->name('tutor.earnings.index');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::put('/admin/tutors/{tutorProfile}/verify', [AdminDashboardController::class, 'verify'])->name('admin.tutors.verify');
    Route::put('/admin/tutors/{tutorProfile}/reject', [AdminDashboardController::class, 'reject'])->name('admin.tutors.reject');
    Route::put('/admin/tutors/{tutorProfile}/revert', [AdminDashboardController::class, 'revert'])->name('admin.tutors.revert');

    Route::get('/admin/finances', [\App\Http\Controllers\AdminFinanceController::class, 'index'])->name('admin.finances.index');
});

use App\Models\TutorProfile;
use App\Models\Subject;

Route::get('/', function () {
    $allSubjects = Subject::orderBy('name')->get();

    $topTutors = TutorProfile::where('verification_status', 'verified')
        ->with(['user', 'subjects', 'reviews'])
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->orderByDesc('reviews_avg_rating')
        ->take(6)
        ->get();

    if ($topTutors->isEmpty()) {
        $topTutors = TutorProfile::whereNotNull('bio')
            ->whereNotNull('hourly_rate')
            ->with(['user', 'subjects', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->take(6)
            ->get();
    }

    return view('welcome', compact('topTutors', 'allSubjects'));
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect(auth()->user()->dashboardUrl());
    })->name('dashboard');
});
