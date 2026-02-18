<?php
/**
 * Update Booking Status API Controller
 * Handles confirm, decline, and cancel actions for tutor bookings.
 */

session_start();

require_once '../functions/db.php';
require_once '../functions/authFunctions.php';
require_once '../functions/tutorFunctions.php';
require_once '../functions/flash.php';

// 1. Security Check: Ensure user is logged in and is a tutor
if (!requireAuth('tutor')) {
    header('Location: ../pages/auth.php');
    exit();
}

// 2. Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/tutor/dashboard.php');
    exit();
}

// 3. Receive and validate POST data
$bookingId = $_POST['booking_id'] ?? null;
$action    = $_POST['action'] ?? null;

if (!$bookingId || !in_array($action, ['confirm', 'decline', 'cancel'])) {
    setFlash("error", "Invalid request parameters.");
    header('Location: ../pages/tutor/dashboard.php');
    exit();
}

// 4. Map action to database status
$newStatus = ($action === 'confirm') ? 'confirmed' : 'cancelled';

// 5. Update status via model
if (updateBookingStatus($bookingId, $newStatus)) {
    if ($action === 'confirm') {
        setFlash("success", "Action completed successfully.");
    } else {
        setFlash("error", "Action was rejected.");
    }
} else {
    setFlash("error", "Failed to update booking status.");
}

// 6. Redirect back to dashboard
header('Location: ../pages/tutor/dashboard.php');
exit();
