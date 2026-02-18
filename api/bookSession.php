<?php
/**
 * Book Session API Controller
 * Refactored to follow Procedural MVC: logic only, no SQL.
 */

session_start();

// Include dependencies
require_once '../functions/db.php';
require_once '../functions/authFunctions.php';
require_once '../functions/learnerFunctions.php';
require_once '../functions/flash.php';

// 1. Ensure user is logged in and is a learner
if (!requireAuth('learner')) {
    header('Location: ../pages/auth.php');
    exit();
}

// 2. Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/learner/dashboard.php');
    exit();
}
verifyCsrfRequest();

// 3. Receive and validate POST data
$studentId = $_SESSION['user_id'];
$tutorProfileId = $_POST['tutor_id'] ?? null;
$dateInput = $_POST['date'] ?? null; // Expected format: YYYY-MM-DD HH:MM

if (!$tutorProfileId || !$dateInput) {
    setFlash("error", "Tutor ID and Date are required.");
    header('Location: ../pages/learner/dashboard.php');
    exit();
}

// 4. Process Booking
try {
    // Determine subject_id using model function
    $subjectId = getTutorPrimarySubject($tutorProfileId);

    // Calculate times (assuming 1-hour session)
    $startTime = date('Y-m-d H:i:s', strtotime($dateInput));
    $endTime = date('Y-m-d H:i:s', strtotime($dateInput . ' +1 hour'));

    $bookingData = [
        'student_id' => $studentId,
        'tutor_id'   => $tutorProfileId,
        'subject_id' => $subjectId,
        'start_time' => $startTime,
        'end_time'   => $endTime
    ];

    // Create booking via model
    if (createBooking($bookingData)) {
        setFlash("info", "Booking request sent. Awaiting tutor confirmation.");
    } else {
        setFlash("error", "This slot is no longer available.");
        header('Location: ../pages/learner/book.php?tutor_id=' . $tutorProfileId);
        exit();
    }

} catch (Exception $e) {
    error_log("Booking error trace: " . $e->getMessage());
    setFlash("error", "A server error occurred. Please contact support.");
}

// 5. Final Redirect
header('Location: ../pages/learner/dashboard.php');
exit();
?>
