<?php
/**
 * Learner Dashboard Model Functions
 * Handles data retrieval for the learner profile and bookings.
 * Requires `db.php` for database access.
 */

/**
 * Retrieves the profile information for a specific learner.
 * 
 * @param int $learnerId The unique ID of the learner.
 * @return array|bool The learner's profile data as an associative array, or false if not found.
 */
function getLearnerProfile($userId) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT user_id, name, email, timezone FROM users WHERE user_id = ? AND role = 'learner' LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getLearnerProfile: " . $e->getMessage());
        return false;
    }
}

function updateLearnerProfile($userId, $name, $timezone) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, timezone = ? WHERE user_id = ?");
        return $stmt->execute([$name, $timezone, $userId]);
    } catch (PDOException $e) {
        error_log("Error in updateLearnerProfile: " . $e->getMessage());
        return false;
    }
}

/**
 * Retrieves all bookings associated with a specific learner.
 * Includes tutor name and subject name.
 * 
 * @param int $learnerId The unique ID of the learner.
 * @return array|bool An array of bookings, or false on error.
 */
function getLearnerBookings($userId) {
    global $pdo;

    try {
        $sql = "SELECT b.booking_id, u.name AS tutor_name, s.subject_name, 
                       b.start_time, b.end_time, b.status,
                       (SELECT COUNT(*) FROM reviews r WHERE r.booking_id = b.booking_id) as has_review
                FROM bookings b
                JOIN tutorprofiles tp ON b.tutor_id = tp.profile_id
                JOIN users u ON tp.user_id = u.user_id
                LEFT JOIN subjects s ON b.subject_id = s.subject_id
                WHERE b.student_id = :user_id
                ORDER BY b.start_time DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getLearnerBookings: " . $e->getMessage());
        return false;
    }
}
/**
 * Retrieves all available tutors for the marketplace.
 * 
 * @return array|bool An array of tutor data, or false on failure.
 */
function getTutors($searchTerm = null) {
    global $pdo;

    try {
        $sql = "SELECT u.user_id, tp.profile_id, u.name, u.timezone,
                       tp.hourly_rate, tp.bio, tp.status AS verification_status, tp.subjects,
                       (SELECT subject_name FROM subjects s 
                        JOIN tutorsubjects ts ON s.subject_id = ts.subject_id 
                        WHERE ts.profile_id = tp.profile_id LIMIT 1) as primary_subject,
                       (SELECT AVG(rating) FROM reviews r 
                        JOIN bookings b ON r.booking_id = b.booking_id 
                        WHERE b.tutor_id = tp.profile_id) as avg_rating,
                       (SELECT COUNT(*) FROM reviews r 
                        JOIN bookings b ON r.booking_id = b.booking_id 
                        WHERE b.tutor_id = tp.profile_id) as review_count
                FROM users u
                JOIN tutorprofiles tp ON u.user_id = tp.user_id
                WHERE u.role = 'tutor' AND tp.status != 'rejected'";
        
        if ($searchTerm) {
            $sql .= " AND tp.subjects LIKE :search";
        }
        
        $stmt = $pdo->prepare($sql);
        
        if ($searchTerm) {
            $stmt->execute([':search' => '%' . $searchTerm . '%']);
        } else {
            $stmt->execute();
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getTutors: " . $e->getMessage());
        return false;
    }
}

/**
 * Retrieves the primary subject ID for a specific tutor profile.
 * 
 * @param int $profileId
 * @return int|null The subject ID or null if not found.
 */
function getTutorPrimarySubject($profileId) {
    global $pdo;

    try {
        $sql = "SELECT subject_id FROM tutorsubjects WHERE profile_id = :profile_id LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':profile_id' => $profileId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? (int)$result['subject_id'] : null;
    } catch (PDOException $e) {
        error_log("Error in getTutorPrimarySubject: " . $e->getMessage());
        return false;
    }
}

/**
 * Retrieves the availability schedule for a specific tutor.
 * 
 * @param int $profileId
 * @return array|bool List of availability slots or false on error.
 */
function getTutorAvailability($profileId) {
    global $pdo;

    try {
        $sql = "SELECT a.availability_id, a.day_of_week, a.start_time, a.end_time 
                FROM availability a
                JOIN tutorprofiles tp ON a.profile_id = tp.profile_id
                WHERE a.profile_id = :profile_id 
                ORDER BY a.day_of_week, a.start_time";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':profile_id' => $profileId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) {
        error_log("Error in getTutorAvailability: " . $e->getMessage());
        return false;
    }
}

/**
 * Retrieves the timezone for a specific tutor profile.
 * 
 * @param int $profileId
 * @return string|null The timezone or null if not found.
 */
function getTutorTimezone($profileId) {
    global $pdo;

    try {
        $sql = "SELECT u.timezone 
                FROM users u
                JOIN tutorprofiles tp ON u.user_id = tp.user_id
                WHERE tp.profile_id = :profile_id 
                LIMIT 1";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':profile_id' => $profileId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['timezone'] : null;
    } catch (PDOException $e) {
        error_log("Error in getTutorTimezone: " . $e->getMessage());
        return false;
    }
}

/**
 * Retrieves simplified tutor details for the booking header.
 * 
 * @param int $profileId
 * @return array|bool
 */
function getTutorDetails($profileId) {
    global $pdo;
    try {
        $sql = "SELECT u.name, u.timezone, tp.hourly_rate,
                       (SELECT subject_name FROM subjects s 
                        JOIN tutorsubjects ts ON s.subject_id = ts.subject_id 
                        WHERE ts.profile_id = tp.profile_id LIMIT 1) as primary_subject
                FROM users u
                JOIN tutorprofiles tp ON u.user_id = tp.user_id
                WHERE tp.profile_id = :profile_id LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':profile_id' => $profileId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getTutorDetails: " . $e->getMessage());
        return false;
    }
}

/**
 * Creates a new booking in the database.
 * includes a check to prevent overlapping bookings for the same tutor/time.
 * 
 * @param array $data Expected keys: 'student_id', 'tutor_id', 'subject_id', 'start_time', 'end_time'
 * @return bool True on success, false on failure or collision.
 */
function createBooking($data) {
    global $pdo;

    try {
        // 0. Check if tutor profile still exists and is approved
        $checkProfSql = "SELECT COUNT(*) FROM tutorprofiles WHERE profile_id = :tutor_id";
        $checkProfStmt = $pdo->prepare($checkProfSql);
        $checkProfStmt->execute([':tutor_id' => $data['tutor_id']]);
        if ($checkProfStmt->fetchColumn() == 0) {
            return false;
        }

        // 1. Collision check: check if tutor already has a booking at this start time
        $checkColSql = "SELECT COUNT(*) FROM bookings WHERE tutor_id = :tutor_id AND start_time = :start_time";
        $checkColStmt = $pdo->prepare($checkColSql);
        $checkColStmt->execute([
            ':tutor_id'   => $data['tutor_id'],
            ':start_time' => $data['start_time']
        ]);

        if ($checkColStmt->fetchColumn() > 0) {
            return false;
        }

        // 2. Insert the booking
        $sql = "INSERT INTO bookings (student_id, tutor_id, subject_id, start_time, end_time, status) 
                VALUES (:student_id, :tutor_id, :subject_id, :start_time, :end_time, 'pending')";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':student_id' => $data['student_id'],
            ':tutor_id'   => $data['tutor_id'],
            ':subject_id' => $data['subject_id'],
            ':start_time' => $data['start_time'],
            ':end_time'   => $data['end_time']
        ]);
    } catch (PDOException $e) {
        error_log("Error in createBooking: " . $e->getMessage());
        return false;
    }
}

/**
 * Submits a review and marks the booking as completed.
 */
function submitReview($bookingId, $rating, $comment = null) {
    global $pdo;
    try {
        $pdo->beginTransaction();

        // 1. Insert the review
        $sqlReview = "INSERT INTO reviews (booking_id, rating, comment) VALUES (?, ?, ?)";
        $stmtReview = $pdo->prepare($sqlReview);
        $stmtReview->execute([$bookingId, $rating, $comment]);

        // 2. Update booking status to 'completed'
        $sqlBooking = "UPDATE bookings SET status = 'completed' WHERE booking_id = ?";
        $stmtBooking = $pdo->prepare($sqlBooking);
        $stmtBooking->execute([$bookingId]);

        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log("Error in submitReview: " . $e->getMessage());
        return false;
    }
}
?>
