<?php
/**
 * Flash Message Helper
 * Handles setting and retrieving session-based flash messages.
 */

// Safely start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Sets a flash message in the session.
 * 
 * @param string $type The type of message (success, error, info).
 * @param string $message The message content.
 * @return void
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Retrieves and clears the flash message from the session.
 * 
 * @return array|null The flash message data ['type', 'message'] or null if none exists.
 */
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
