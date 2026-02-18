<?php
/**
 * Login Controller
 * Handles user authentication requests and redirects based on roles.
 */

session_start();
// Include dependencies
require_once '../functions/db.php';
require_once '../functions/authFunctions.php';
require_once '../functions/flash.php';

// Check if search request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfRequest();
    // Sanitize and retrieve input
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    // Attempt login
    $loginResult = loginUser($email, $password);

    if ($loginResult === true) {
        // Redirect based on role
        $role = $_SESSION['role'] ?? '';
        
        switch ($role) {
            case 'learner':
                header('Location: ../pages/learner/dashboard.php');
                break;
            case 'tutor':
                header('Location: ../pages/tutor/dashboard.php');
                break;
            case 'admin':
                header('Location: ../pages/admin/verifications.php');
                break;
            default:
                header('Location: ../pages/auth.php');
                break;
        }
    } elseif ($loginResult === 'unverified') {
        // block login and show specific verification message
        setFlash('error', 'Please verify your email before logging in');
        header('Location: ../pages/auth.php');
    } else {
        // Redirect back with error message
        setFlash('error', 'Invalid email or password.');
        header('Location: ../pages/auth.php');
    }
    exit;
} else {
    // If not a POST request, redirect to auth page
    header('Location: ../pages/auth.php');
    exit;
}
?>
