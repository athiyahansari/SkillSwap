<?php
/**
 * OTP Verification API
 * Validates the 6-digit code and activates the user account.
 */

session_start();
require_once '../functions/db.php';
require_once '../functions/authFunctions.php';
require_once '../functions/flash.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfRequest();
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $otp   = trim($_POST['otp'] ?? '');

    if (!$email || strlen($otp) !== 6) {
        setFlash('error', 'Invalid verification attempt. Please provide a 6-digit code.');
        header('Location: ../pages/auth/verify-otp.php');
        exit;
    }

    try {
        // Find user by email and OTP
        $stmt = $pdo->prepare("SELECT user_id, role, name FROM users WHERE email = ? AND otp_code = ? AND is_verified = 0");
        $stmt->execute([$email, $otp]);
        $user = $stmt->fetch();

        if ($user) {
            // Update user status and clear OTP
            $updateStmt = $pdo->prepare("UPDATE users SET is_verified = 1, otp_code = NULL WHERE user_id = ?");
            if ($updateStmt->execute([$user['user_id']])) {
                // Clear unverified session data
                unset($_SESSION['unverified_email']);

                // Success message and prompt to login
                setFlash('success', "Account verified successfully! Please log in with your credentials.");
                header('Location: ../pages/auth.php');
                exit();
            } else {
                setFlash('error', 'Failed to update account status. Please try again.');
                header('Location: ../pages/auth/verify-otp.php');
                exit();
            }
        } else {
            setFlash('error', 'Invalid or expired verification code. Please try again.');
            header('Location: ../pages/auth/verify-otp.php');
        }
    } catch (PDOException $e) {
        error_log("OTP verification error: " . $e->getMessage());
        setFlash('error', 'An unexpected error occurred. Please try again later.');
        header('Location: ../pages/auth/verify-otp.php');
    }
    exit;
} else {
    header('Location: ../pages/auth.php');
    exit;
}
