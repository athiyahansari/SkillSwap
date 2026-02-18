<?php
/**
 * Tutor Dashboard Model Functions
 * Handles data retrieval for tutor profiles and their teaching sessions.
 * Requires `db.php` for database access.
 */

/**
 * Retrieves the full profile information for a tutor.
 * Joins 'users' and 'tutorprofiles' tables.
 * 
 * @param int $tutorUserId The user_id of the tutor.
 * @return array|bool Tutor profile data or false if not found.
 */
function getTutorProfile($tutorUserId) {
    global $pdo;

    try {
        $sql = "SELECT u.user_id, u.name, u.email, 
                       tp.profile_id, tp.hourly_rate, tp.bio, tp.status as verification_status, tp.subjects,
                       (SELECT s.subject_name FROM subjects s 
                        JOIN tutorsubjects ts ON s.subject_id = ts.subject_id 
                        WHERE ts.profile_id = tp.profile_id LIMIT 1) as subject_name,
                       (SELECT AVG(rating) FROM reviews r 
                        JOIN bookings b ON r.booking_id = b.booking_id 
                        WHERE b.tutor_id = tp.profile_id) as avg_rating,
                       (SELECT COUNT(*) FROM reviews r 
                        JOIN bookings b ON r.booking_id = b.booking_id 
                        WHERE b.tutor_id = tp.profile_id) as review_count
                FROM users u
                JOIN tutorprofiles tp ON u.user_id = tp.user_id
                WHERE u.user_id = :user_id AND u.role = 'tutor'
                LIMIT 1";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $tutorUserId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getTutorProfile: " . $e->getMessage());
        return false;
    }
}

/**
 * Retrieves all sessions (bookings) for a specific tutor.
 * Joins 'bookings', 'users' (for student name), and 'subjects'.
 * 
 * @param int $tutorUserId The user_id of the tutor.
 * @return array|bool List of sessions or false on failure.
 */
function getTutorSessions($tutorUserId) {
    global $pdo;

    try {
        // First, get the profile_id for this user_id
        $profileSql = "SELECT profile_id FROM tutorprofiles WHERE user_id = :user_id LIMIT 1";
        $pStmt = $pdo->prepare($profileSql);
        $pStmt->execute([':user_id' => $tutorUserId]);
        $profile = $pStmt->fetch(PDO::FETCH_ASSOC);

        if (!$profile) return [];

        $profileId = $profile['profile_id'];

        // Now, get bookings for this profile_id
        $sql = "SELECT b.booking_id, b.start_time, b.end_time, b.status, b.meeting_link,
                       u.name AS student_name, u.email AS student_email,
                       s.subject_name
                FROM bookings b
                JOIN users u ON b.student_id = u.user_id
                LEFT JOIN subjects s ON b.subject_id = s.subject_id
                WHERE b.tutor_id = :profile_id
                ORDER BY b.start_time DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':profile_id' => $profileId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getTutorSessions: " . $e->getMessage());
        return false;
    }
}

/**
 * Updates or adds an availability slot for a tutor.
 * 
 * @param int $tutorUserId The user_id of the tutor.
 * @param string $day Day of the week (Mon, Tue, etc.)
 * @param string $start Start time (HH:MM)
 * @param string $end End time (HH:MM)
 * @return bool True on success, false on failure.
 */
function updateTutorAvailability($tutorUserId, $day, $start, $end) {
    global $pdo;

    try {
        // Resolve profile_id
        $profileSql = "SELECT profile_id FROM tutorprofiles WHERE user_id = :user_id LIMIT 1";
        $pStmt = $pdo->prepare($profileSql);
        $pStmt->execute([':user_id' => $tutorUserId]);
        $profile = $pStmt->fetch(PDO::FETCH_ASSOC);

        if (!$profile) return false;

        $profileId = $profile['profile_id'];

        // Convert times to timestamps to split into 1-hour slots
        $current = strtotime($start);
        $finish = strtotime($end);

        if ($current >= $finish) return false;

        $pdo->beginTransaction();

        $sql = "INSERT INTO availability (profile_id, day_of_week, start_time, end_time) 
                VALUES (:profile_id, :day, :slot_start, :slot_end)";
        $stmt = $pdo->prepare($sql);

        while ($current < $finish) {
            $slotStart = date('H:i:s', $current);
            $nextHour = $current + 3600;
            $slotEnd = date('H:i:s', $nextHour);

            $stmt->execute([
                ':profile_id' => $profileId,
                ':day'        => $day,
                ':slot_start' => $slotStart,
                ':slot_end'   => $slotEnd
            ]);

            $current = $nextHour;
        }

        $pdo->commit();
        return true;

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Error in updateTutorAvailability: " . $e->getMessage());
        return false;
    }
}

/**
 * Retrieves tutor profile details by user ID.
 * 
 * @param int $userId The user_id of the tutor.
 * @return array|bool Tutor profile data or false on failure.
 */
function getTutorProfileByUserId($userId) {
    global $pdo;
    try {
        $sql = "SELECT u.user_id, u.name, u.email, 
                       tp.profile_id, tp.hourly_rate, tp.bio, tp.status, tp.subjects,
                       (SELECT AVG(rating) FROM reviews r 
                        JOIN bookings b ON r.booking_id = b.booking_id 
                        WHERE b.tutor_id = tp.profile_id) as avg_rating,
                       (SELECT COUNT(*) FROM reviews r 
                        JOIN bookings b ON r.booking_id = b.booking_id 
                        WHERE b.tutor_id = tp.profile_id) as review_count
                FROM users u
                JOIN tutorprofiles tp ON u.user_id = tp.user_id
                WHERE u.user_id = ? AND u.role = 'tutor'
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getTutorProfileByUserId: " . $e->getMessage());
        return false;
    }
}

/**
 * Updates the tutor's hourly rate, bio, and subjects.
 * 
 * @param int $profileId The profile_id of the tutor.
 * @param float $hourlyRate
 * @param string $bio
 * @param string|null $subjects Comma-separated list of subjects
 * @return bool True on success, false on failure.
 */
function updateTutorProfile($profileId, $hourlyRate, $bio, $subjects = null) {
    global $pdo;
    try {
        $sql = "UPDATE tutorprofiles SET hourly_rate = ?, bio = ?, subjects = ? WHERE profile_id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$hourlyRate, $bio, $subjects, $profileId]);
    } catch (PDOException $e) {
        error_log("Error in updateTutorProfile: " . $e->getMessage());
        return false;
    }
}

/**
 * Retrieves a financial summary for a tutor based on confirmed bookings.
 * 
 * @param int $profileId The profile_id of the tutor.
 * @return array|bool Associative array of finance data or false on failure.
 */
function getTutorFinanceSummary($tutorUserId) {
    global $pdo;

    try {
        $sql = "SELECT COUNT(b.booking_id) as total_sessions,
                       SUM(p.tutor_earnings) as total_earnings,
                       SUM(p.platform_fee) as total_platform_fee
                FROM bookings b
                JOIN payments p ON b.booking_id = p.booking_id
                JOIN tutorprofiles tp ON b.tutor_id = tp.profile_id
                WHERE tp.user_id = :user_id AND b.status IN ('confirmed', 'completed')";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $tutorUserId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getTutorFinanceSummary: " . $e->getMessage());
        return false;
    }
}

/**
 * Retrieves recent confirmed earnings for a specific tutor.
 * 
 * @param int $profileId The profile_id of the tutor.
 * @param int $limit Number of records to return.
 * @return array|bool List of recent earnings or false on failure.
 */
function getTutorRecentEarnings($tutorUserId, $limit = 10) {
    global $pdo;

    try {
        // Relax strict filters to include 'completed' and 'confirmed' bookings
        // Also relax subject join to ensure rows show even if link is broken
        $sql = "SELECT p.payment_id, b.booking_id, u.name as student_name, s.subject_name, 
                       b.start_time as session_date, p.tutor_earnings, p.total_amount,
                       p.platform_fee, p.payment_status
                FROM bookings b
                JOIN users u ON b.student_id = u.user_id
                LEFT JOIN subjects s ON b.subject_id = s.subject_id
                JOIN payments p ON b.booking_id = p.booking_id
                JOIN tutorprofiles tp ON b.tutor_id = tp.profile_id
                WHERE tp.user_id = :user_id 
                AND b.status IN ('confirmed', 'completed')
                ORDER BY b.start_time DESC
                LIMIT :limit";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id', $tutorUserId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getTutorRecentEarnings: " . $e->getMessage());
        return false;
    }
}

/**
 * Updates the status of a booking.
 * 
 * @param int $bookingId
 * @param string $status ('confirmed', 'cancelled')
 * @return bool True on success, false on failure.
 */
function updateBookingStatus($bookingId, $status) {
    global $pdo;
    
    // Validate status
    if (!in_array($status, ['confirmed', 'cancelled', 'completed'])) {
        return false;
    }

    try {
        $sql = "UPDATE bookings SET status = :status WHERE booking_id = :booking_id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':booking_id' => $bookingId
        ]);
    } catch (PDOException $e) {
        error_log("Error in updateBookingStatus: " . $e->getMessage());
        return false;
    }
}

/**
 * Retrieves reviews for a specific tutor profile.
 */
function getTutorReviews($profileId) {
    global $pdo;
    try {
        $sql = "SELECT r.rating, r.comment, u.name as student_name, b.start_time
                FROM reviews r
                JOIN bookings b ON r.booking_id = b.booking_id
                JOIN users u ON b.student_id = u.user_id
                WHERE b.tutor_id = ?
                ORDER BY b.start_time DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$profileId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getTutorReviews: " . $e->getMessage());
        return false;
    }
}
?>
