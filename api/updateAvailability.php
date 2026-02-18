<?php
/**
 * Update Availability API Controller
 * Processes availability updates for tutors.
 */

session_start();

// Include dependencies
require_once '../functions/db.php';
require_once '../functions/authFunctions.php';
require_once '../functions/tutorFunctions.php';

// 1. Validate tutor session
if (!requireAuth('tutor')) {
    header('Location: ../pages/auth.php');
    exit();
}

// 2. Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/tutor/dashboard.php');
    exit();
}

// 3. Receive and sanitize data
$tutorUserId = $_SESSION['user_id'];
$day         = $_POST['day'] ?? '';
$startTime   = $_POST['start_time'] ?? '';
$endTime     = $_POST['end_time'] ?? '';

// 4. Basic validation
if (empty($day) || empty($startTime) || empty($endTime)) {
    setFlash('error', "All availability fields are required.");
    header('Location: ../pages/tutor/dashboard.php');
    exit();
}

// 5. Call model function
if (updateTutorAvailability($tutorUserId, $day, $startTime, $endTime)) {
    setFlash('success', "Availability updated successfully!");
} else {
    setFlash('error', "Failed to update availability. Please try again.");
}

// 6. Redirect back to dashboard
header('Location: ../pages/tutor/dashboard.php');
exit();
