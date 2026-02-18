<?php
session_start();
require_once '../functions/db.php';
require_once '../functions/authFunctions.php';
require_once '../functions/learnerFunctions.php';

// 1. Auth check
if (!requireAuth('learner')) {
    header('Location: ../pages/auth.php');
    exit();
}

// 2. POST Check
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/learner/bookings.php');
    exit();
}

// 3. Inputs
$bookingId = $_POST['booking_id'] ?? null;
$rating    = $_POST['rating'] ?? null;
$comment   = $_POST['comment'] ?? null;
$userId    = $_SESSION['user_id'];

// 4. Validation
if (!$bookingId || !$rating || $rating < 1 || $rating > 5) {
    setFlash('error', "Invalid rating or booking ID.");
    header('Location: ../pages/learner/bookings.php');
    exit();
}

try {
    // Check if booking exists, belongs to user, is confirmed, and end_time < NOW
    // and no existing review
    $sql = "SELECT b.booking_id, b.end_time, b.status,
                   (SELECT COUNT(*) FROM reviews r WHERE r.booking_id = b.booking_id) as review_count
            FROM bookings b
            WHERE b.booking_id = ? AND b.student_id = ?
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$bookingId, $userId]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        setFlash('error', "Booking not found or unauthorized.");
        header('Location: ../pages/learner/bookings.php');
        exit();
    }

    if ($booking['status'] !== 'confirmed') {
        setFlash('error', "Only confirmed sessions can be reviewed.");
        header('Location: ../pages/learner/bookings.php');
        exit();
    }

    if (strtotime($booking['end_time']) > time()) {
        setFlash('error', "You can only review a session after it has ended.");
        header('Location: ../pages/learner/bookings.php');
        exit();
    }

    if ($booking['review_count'] > 0) {
        setFlash('error', "You have already reviewed this session.");
        header('Location: ../pages/learner/bookings.php');
        exit();
    }

    // 5. Submit Review
    if (submitReview($bookingId, $rating, $comment)) {
        setFlash('success', "Review submitted! Session is now marked as completed.");
    } else {
        setFlash('error', "Failed to submit review.");
    }

} catch (PDOException $e) {
    error_log("Error in submitReview API: " . $e->getMessage());
    setFlash('error', "An error occurred while submitting your review.");
}

header('Location: ../pages/learner/bookings.php');
exit();
?>
