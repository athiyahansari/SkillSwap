<?php
/**
 * Logout Controller
 * Handles user logout by destroying the session and redirecting to the landing page.
 */

require_once '../functions/authFunctions.php';

// Destroy session
logoutUser();

// Redirect to landing page
header('Location: ../pages/auth.php');
exit;
?>
