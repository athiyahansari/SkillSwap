<?php
/**
 * Tutor Verification Controller
 * Handles admin approval or rejection of tutor profiles.
 */

session_start();

// Include dependencies
require_once '../functions/db.php';
require_once '../functions/authFunctions.php';
require_once '../functions/adminFunctions.php';
require_once '../functions/flash.php';

// 1. Protect route: Ensure only admins can access
if (!requireAuth('admin')) {
    header('Location: ../pages/auth.php');
    exit();
}

// 2. Accept ONLY POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/admin/verifications.php');
    exit();
}
verifyCsrfRequest();

// 3. Read POST values
$profileId = $_POST['profile_id'] ?? null;
$action    = $_POST['action'] ?? null;

// 4. Validate inputs
if (!$profileId || !in_array($action, ['verify', 'reject', 'reset'])) {
    setFlash("error", "Invalid verification request.");
    header('Location: ../pages/admin/verifications.php');
    exit();
}

// 5. Call model functions
$success = false;
if ($action === 'verify') {
    $success = approveTutorProfile($profileId);
} elseif ($action === 'reject') {
    $success = rejectTutorProfile($profileId);
} elseif ($action === 'reset') {
    $success = revokeTutorVerification($profileId);
}

// 6. Provide Feedback
if ($success) {
    setFlash("success", "Tutor verification status updated.");
} else {
    setFlash("error", "Failed to perform verification action.");
}

// 7. Redirect back to admin verifications page
header('Location: ../pages/admin/verifications.php');
exit();
