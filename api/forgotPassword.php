<?php
/**
 * Forgot Password Controller
 * Handles password reset requests and generates simulated links.
 */

session_start();
require_once '../functions/db.php';
require_once '../functions/authFunctions.php';
require_once '../functions/flash.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfRequest();
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (!$email) {
        setFlash('error', 'Please provide a valid email address.');
        header('Location: ../pages/auth/forgot-password.php');
        exit;
    }

    try {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Generate secure token
            $token = bin2hex(random_bytes(32));

            // Store token in database
            $updateStmt = $pdo->prepare("UPDATE users SET reset_token = ? WHERE user_id = ?");
            if (!$updateStmt->execute([$token, $user['user_id']])) {
                throw new PDOException("Failed to update reset token in database.");
            }

            // Simulate sending reset link
            $resetLink = "../pages/auth/reset-password.php?token=" . $token;
            
            // Output simulated reset link as requested
            echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <title>Password Reset</title>
                <script src='https://cdn.tailwindcss.com'></script>
            </head>
            <body class='bg-slate-50 min-h-screen flex items-center justify-center p-6'>
                <div class='max-w-md w-full bg-white p-8 rounded-[2rem] shadow-xl border border-slate-100'>
                    <h2 class='text-2xl font-bold text-slate-900 mb-4'>Reset your password</h2>
                    <p class='text-slate-500 mb-6'>We've sent a password reset link to <strong>" . htmlspecialchars($email) . "</strong>.</p>
                    <div class='bg-indigo-50 p-4 rounded-xl border border-indigo-100 break-all mb-6'>
                        <a href='" . $resetLink . "' class='text-indigo-600 font-bold hover:underline'>" . $resetLink . "</a>
                    </div>
                    <a href='../pages/auth.php' class='block text-center bg-slate-900 text-white font-bold py-4 rounded-xl hover:bg-slate-800 transition'>Return to Login</a>
                </div>
            </body>
            </html>";
        } else {
            setFlash('error', 'No account found with that email address.');
            header('Location: ../pages/auth/forgot-password.php');
        }
    } catch (PDOException $e) {
        error_log("Forgot password error: " . $e->getMessage());
        setFlash('error', 'An unexpected error occurred. Please try again later.');
        header('Location: ../pages/auth/forgot-password.php');
    }
    exit;
} else {
    header('Location: ../pages/auth/forgot-password.php');
    exit;
}
