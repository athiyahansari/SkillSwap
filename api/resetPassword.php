<?php
/**
 * Reset Password Controller
 * Updates user's password using a verification token.
 */

session_start();
require_once '../functions/db.php';
require_once '../functions/authFunctions.php';
require_once '../functions/flash.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfRequest();
    $token = $_POST['token'] ?? null;
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (!$token) {
        setFlash('error', 'Invalid request. Token missing.');
        header('Location: ../pages/auth/forgot-password.php');
        exit;
    }

    if (strlen($password) < 6) {
        setFlash('error', 'Password must be at least 6 characters long.');
        header('Location: ../pages/auth/reset-password.php?token=' . urlencode($token));
        exit;
    }

    if ($password !== $confirmPassword) {
        setFlash('error', 'Passwords do not match.');
        header('Location: ../pages/auth/reset-password.php?token=' . urlencode($token));
        exit;
    }

    try {
        // Verify token again before update
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE reset_token = ?");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            // Password strength validation
            $passCheck = validatePasswordStrength($password);
            if (!$passCheck['valid']) {
                setFlash('error', $passCheck['message']);
                header('Location: ../pages/auth/reset-password.php?token=' . urlencode($token));
                exit();
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Update user's password and clear token
            if ($updateStmt->execute([$hashedPassword, $user['user_id']])) {
                setFlash('success', 'Your password has been reset successfully. Please log in with your new password.');
                header('Location: ../pages/auth.php');
            } else {
                setFlash('error', 'Failed to update your password. Please try again.');
                header('Location: ../pages/auth/reset-password.php?token=' . urlencode($token));
            }
        } else {
            setFlash('error', 'Invalid or expired reset token.');
            header('Location: ../pages/auth/forgot-password.php');
        }
    } catch (PDOException $e) {
        error_log("Password reset error: " . $e->getMessage());
        setFlash('error', 'An unexpected error occurred. Please try again later.');
        header('Location: ../pages/auth/reset-password.php?token=' . urlencode($token));
    }
    exit;
} else {
    header('Location: ../pages/auth/forgot-password.php');
    exit;
}
