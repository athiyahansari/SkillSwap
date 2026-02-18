<?php
/**
 * Update Tutor Profile Controller
 * Handles POST requests to update tutor profile details.
 */

session_start();

// Include dependencies
require_once '../functions/db.php';
require_once '../functions/authFunctions.php';
require_once '../functions/tutorFunctions.php';

// 1. Ensure user is logged in and is a tutor
if (!requireAuth('tutor')) {
    header('Location: ../pages/auth.php');
    exit();
}

// 2. Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/tutor/profile.php');
    exit();
}
verifyCsrfRequest();

// 3. Read POST inputs
$submittedProfileId = $_POST['profile_id'] ?? '';
$hourlyRate         = $_POST['hourly_rate'] ?? '';
$bio                = $_POST['bio'] ?? '';
$subjectsArray      = $_POST['subjects'] ?? [];

// Security: Enforce ownership - Get actual profile ID for logged-in user
$userId = $_SESSION['user_id'];
$profile = getTutorProfileByUserId($userId);

if (!$profile || $profile['profile_id'] != $submittedProfileId) {
    setFlash('error', "Unauthorized profile update attempt.");
    header('Location: ../pages/tutor/profile.php');
    exit();
}

$profileId = $profile['profile_id'];

// 4. Validate inputs & Process Subjects
if (!is_numeric($hourlyRate) || floatval($hourlyRate) <= 0) {
    setFlash('error', "Invalid hourly rate.");
    header('Location: ../pages/tutor/profile.php');
    exit();
}

// Convert subjects array to comma-separated string
$subjectsCsv = !empty($subjectsArray) ? implode(',', array_map('trim', $subjectsArray)) : null;

// 5. Call Model function
if (updateTutorProfile($profileId, floatval($hourlyRate), $bio, $subjectsCsv)) {
    setFlash('success', "Profile updated successfully");
} else {
    setFlash('error', "Failed to update profile data");
}

// 6. Final Redirect
header('Location: ../pages/tutor/profile.php');
exit();
?>
