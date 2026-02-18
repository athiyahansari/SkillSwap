<?php
/**
 * Security Helper Functions
 * This file provides utilities for CSRF protection and XSS mitigation.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate a secure CSRF token if one doesn't exist.
 * @return string
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Get the current CSRF token.
 * @return string
 */
function getCsrfToken() {
    return generateCsrfToken();
}

/**
 * Validate a provided token against the session token.
 * @param string $token
 * @return bool
 */
function validateCsrfToken($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Escape output for XSS prevention.
 * @param string $value
 * @return string
 */
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate a hidden CSRF input field for forms.
 * @return string
 */
function csrf_field() {
    $token = getCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Validate CSRF for any POST request automatically or manually.
 * Rejects with a 403 error if invalid.
 */
function verifyCsrfRequest() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!validateCsrfToken($token)) {
            header('HTTP/1.1 403 Forbidden');
            die('Invalid CSRF token.');
        }
    }
}
?>
