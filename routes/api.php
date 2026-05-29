<?php

use App\Http\Controllers\Api\LearnerBookingController;
use App\Http\Controllers\Api\TutorController;
use App\Http\Controllers\Api\TutorEarningController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes
Route::get('/tutors', [TutorController::class, 'index']);

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Authenticated User Profile
    Route::get('/user/profile', [UserController::class, 'profile']);

    // Learner Specific APIs
    Route::middleware('role:learner')->group(function () {
        Route::get('/learner/bookings', [LearnerBookingController::class, 'index']);
    });

    // Tutor Specific APIs
    Route::middleware('role:tutor')->group(function () {
        Route::get('/tutor/earnings', [TutorEarningController::class, 'index']);
    });
});
