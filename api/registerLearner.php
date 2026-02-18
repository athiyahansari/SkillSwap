<?php
/**
 * Learner Registration API Controller
 * Receives POST data, validates it, and creates a new learner account.
 */

// Start session to store error/success messages
session_start();

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/auth.php");
    exit();
}
verifyCsrfRequest();

// Include dependencies
require_once '../functions/db.php';
require_once '../functions/userFunctions.php';
require_once '../functions/authFunctions.php';
require_once '../functions/flash.php';

// Retrieve and sanitize POST data
$full_name       = trim($_POST['name'] ?? '');
$email           = trim($_POST['email'] ?? '');
$phone           = trim($_POST['phone'] ?? '');
$password        = $_POST['password'] ?? '';
$education_level = trim($_POST['education_level'] ?? '');
$subjects        = trim($_POST['subjects'] ?? '');
$timezone        = trim($_POST['timezone'] ?? '');

// Validate required fields
if (empty($full_name) || empty($email) || empty($password) || empty($education_level) || empty($subjects) || empty($timezone)) {
    setFlash('error', "All fields except Phone Number are required.");
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../pages/learner/register.php'));
    exit();
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setFlash('error', "Invalid email format");
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../pages/learner/register.php'));
    exit();
}

// Password strength validation
$passCheck = validatePasswordStrength($password);
if (!$passCheck['valid']) {
    setFlash('error', $passCheck['message']);
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../pages/learner/register.php'));
    exit();
}

// Check if email already exists
if (emailExists($email)) {
    setFlash('error', "User already registered with this email");
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../pages/learner/register.php'));
    exit();
}

// Split full name into first and last name
$name_parts = explode(' ', $full_name, 2);
$first_name = $name_parts[0];
$last_name  = $name_parts[1] ?? '';

// Generate a 6-digit OTP
$otp_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

// Prepare data for the createLearner function
$data = [
    'first_name'      => $first_name,
    'last_name'       => $last_name,
    'email'           => $email,
    'password'        => $password,
    'phone'           => $phone,
    'education_level' => $education_level,
    'subjects'        => $subjects,
    'timezone'        => $timezone,
    'otp_code'        => $otp_code
];

// Call createLearner()
$result = createLearner($data);

if ($result) {
    // Store email and simulated OTP in session/flash for the verification page
    $_SESSION['unverified_email'] = $email;
    setFlash('info', "Simulated Email: Your verification code is **$otp_code**");

    // Redirect to OTP verification page
    header("Location: ../pages/auth/verify-otp.php");
    exit();
} else {
    // On failure, redirect back with error
    setFlash('error', "Registration failed. Please try again or use a different email.");
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../pages/learner/register.php'));
    exit();
}
?>
