<?php
/**
 * Authentication Helper Functions
 * This file provides procedural methods for user authentication and session management.
 * It requires `db.php` to be included beforehand for database access.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/securityFunctions.php';
generateCsrfToken();

/**
 * Log in a user by verifying their email and password.
 * 
 * @param string $email The user's email address.
 * @param string $password The user's plain-text password.
 * @return bool True on successful login, false otherwise.
 */
function loginUser($email, $password) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT user_id, email, password, role, is_verified FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if (!$user['is_verified']) {
                $_SESSION['unverified_email'] = $user['email'];
                return 'unverified';
            }
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
    } catch (PDOException $e) {
        // Log error or handle as needed
        error_log("Login error: " . $e->getMessage());
    }

    return false;
}

/**
 * Log out the current user and destroy the session.
 * 
 * @return bool Always true.
 */
function logoutUser() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    return true;
}

/**
 * Check if a user is currently logged in.
 * 
 * @return bool True if logged in, false otherwise.
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if the current user is authorized based on login status and optional role.
 * 
 * @param string|null $role Required role (optional).
 * @return bool True if authorized, false otherwise.
 */
function requireAuth($role = null) {
    if (!isLoggedIn()) {
        return false;
    }

    if ($role !== null && (!isset($_SESSION['role']) || $_SESSION['role'] !== $role)) {
        return false;
    }

    return true;
}
/**
 * Validates password strength based on specific rules:
 * - Minimum 8 characters
 * - At least 1 uppercase letter
 * - At least 1 lowercase letter
 * - At least 1 number
 * 
 * @param string $password
 * @return array|bool True if valid, array with error message if invalid.
 */
function validatePasswordStrength($password) {
    if (strlen($password) < 8) {
        return ['valid' => false, 'message' => "Password must be at least 8 characters long."];
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return ['valid' => false, 'message' => "Password must contain at least one uppercase letter."];
    }
    if (!preg_match('/[a-z]/', $password)) {
        return ['valid' => false, 'message' => "Password must contain at least one lowercase letter."];
    }
    if (!preg_match('/[0-9]/', $password)) {
        return ['valid' => false, 'message' => "Password must contain at least one number."];
    }
    return ['valid' => true];
}
?>
