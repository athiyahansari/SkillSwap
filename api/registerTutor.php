<?php
/**
 * Tutor Registration API Controller
 * Receives POST data, validates it, and creates a new tutor account.
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
require_once '../functions/authFunctions.php';
require_once '../functions/userFunctions.php';
require_once '../functions/tutorFunctions.php';
require_once '../functions/flash.php';

// Retrieve and sanitize POST data
$full_name       = trim($_POST['name'] ?? '');
$email           = trim($_POST['email'] ?? '');
$password        = $_POST['password'] ?? '';
$subject         = trim($_POST['subject'] ?? '');
$qualification   = trim($_POST['qualification'] ?? '');
$university_name = trim($_POST['university_name'] ?? '');
$rate            = trim($_POST['rate'] ?? '');
$timezone        = trim($_POST['timezone'] ?? '');
$bio             = trim($_POST['bio'] ?? '');
$phone           = trim($_POST['phone'] ?? '');
$availability    = $_POST['availability'] ?? null; // Expecting array of objects: {day_of_week, start_time, end_time}
$payout_method   = $_POST['payout'] ?? '';
$payout_details  = trim($_POST['payout_details'] ?? '');

// Validate required fields
if (empty($full_name) || empty($email) || empty($password) || empty($subject) || empty($qualification)) {
    setFlash('error', "Required fields (Name, Email, Password, Subject, Qualification) must be filled.");
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../pages/tutor/register.php'));
    exit();
}

// Password strength validation
$passCheck = validatePasswordStrength($password);
if (!$passCheck['valid']) {
    setFlash('error', $passCheck['message']);
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../pages/tutor/register.php'));
    exit();
}

// Check if email already exists
if (emailExists($email)) {
    setFlash('error', "User already registered with this email");
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../pages/tutor/register.php'));
    exit();
}

// Split full name into first and last name
$name_parts = explode(' ', $full_name, 2);
$first_name = $name_parts[0];
$last_name  = $name_parts[1] ?? '';

// Generate a 6-digit OTP
$otp_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

// Prepare data for the createTutor function
$data = [
    'first_name'      => $first_name,
    'last_name'       => $last_name,
    'email'           => $email,
    'password'        => $password,
    'subject'         => $subject,
    'qualification'   => $qualification,
    'university_name' => $university_name,
    'rate'            => $rate,
    'bio'             => $bio,
    'phone'           => $phone,
    'timezone'        => $timezone,
    'payout_method'   => $payout_method,
    'payout_details'  => $payout_details,
    'otp_code'        => $otp_code
];

// Call createTutor()
$result = createTutor($data);

if ($result) {
// 4. Process Availability
if ($result && !empty($availability)) {
    $slots = json_decode($availability, true);
    if (is_array($slots)) {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $start_times = ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00'];
        $end_times   = ['10:00', '12:00', '14:00', '16:00', '18:00', '20:00'];

        foreach ($slots as $index) {
            $day_idx  = $index % 7;
            $time_idx = floor($index / 7);

            if (isset($days[$day_idx]) && isset($start_times[$time_idx])) {
                $day   = $days[$day_idx];
                $start = $start_times[$time_idx];
                $end   = $end_times[$time_idx];
                
                // This model function handles splitting the 2-hour block into 1-hour slots
                updateTutorAvailability($result, $day, $start, $end);
            }
        }
    }
}

    // Store email and simulated OTP in session/flash for the verification page
    $_SESSION['unverified_email'] = $email;
    setFlash('info', "Simulated Email: Your verification code is **$otp_code**");

    // Redirect to OTP verification page
    header("Location: ../pages/auth/verify-otp.php");
    exit();
} else {
    // On failure, redirect back with error
    setFlash('error', "Registration failed. Please try again or use a different email.");
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../pages/tutor/register.php'));
    exit();
}
?>
