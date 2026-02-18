<?php
/**
 * Update Learner Profile Controller
 * Handles POST requests to update learner name and timezone.
 */

session_start();

// Include dependencies
require_once '../functions/db.php';
require_once '../functions/authFunctions.php';
require_once '../functions/learnerFunctions.php';

// 1. Ensure user is logged in and is a learner
if (!requireAuth('learner')) {
    header('Location: ../pages/auth.php');
    exit();
}

// 2. Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/learner/profile.php');
    exit();
}
verifyCsrfRequest();

// 3. Receive and validate POST data
$userId   = $_SESSION['user_id'];
$name     = trim($_POST['name'] ?? '');
$timezone = trim($_POST['timezone'] ?? '');

if (empty($name)) {
    setFlash('error', "Name field is required.");
    header('Location: ../pages/learner/profile.php');
    exit();
}

// 4. Update Profile via Model
if (updateLearnerProfile($userId, $name, $timezone)) {
    setFlash('success', "Profile updated successfully");
} else {
    setFlash('error', "Failed to update profile");
}

// 5. Final Redirect
header('Location: ../pages/learner/profile.php');
exit();
?>
