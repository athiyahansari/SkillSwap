<?php
/**
 * Admin Dashboard Model Functions
 * Handles tutor verification and management.
 * Requires procedural approach with PDO.
 */

/**
 * Retrieves all tutors with 'pending' verification status.
 * 
 * @return array|bool List of pending tutors or false on error.
 */
function getPendingTutors() {
    global $pdo;

    try {
        $sql = "SELECT u.user_id, u.name, u.email, 
                       tp.profile_id, tp.hourly_rate, tp.bio, tp.qualification, tp.university_name, tp.status
                FROM users u
                JOIN tutorprofiles tp ON u.user_id = tp.user_id
                WHERE tp.status = 'pending' AND u.role = 'tutor'";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getPendingTutors: " . $e->getMessage());
        return false;
    }
}

/**
 * Retrieves all tutors with 'verified' verification status.
 * 
 * @return array|bool List of verified tutors or false on error.
 */
function getVerifiedTutors() {
    global $pdo;

    try {
        $sql = "SELECT u.user_id, u.name, u.email, 
                       tp.profile_id, tp.hourly_rate, tp.bio, tp.qualification, tp.university_name, tp.status
                FROM users u
                JOIN tutorprofiles tp ON u.user_id = tp.user_id
                WHERE tp.status = 'verified' AND u.role = 'tutor'";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getVerifiedTutors: " . $e->getMessage());
        return false;
    }
}

/**
 * Updates tutor verification status (single source of truth)
 *
 * @param int $profileId
 * @param string $status ('pending', 'verified', 'rejected')
 * @return bool
 */
function updateTutorStatus($profileId, $status) {
    global $pdo;

    try {
        $sql = "UPDATE tutorprofiles 
                SET status = :status 
                WHERE profile_id = :profile_id";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':profile_id' => $profileId
        ]);
    } catch (PDOException $e) {
        error_log("Error in updateTutorStatus: " . $e->getMessage());
        return false;
    }
}


/**
 * Approves a tutor profile by setting status to 'verified'.
 * 
 * @param int $profileId The ID of the profile to approve.
 * @return bool True on success, false on failure.
 */
function approveTutorProfile($profileId) {
    return updateTutorStatus($profileId, 'verified');
}


/**
 * Rejects a tutor profile by deleting it from the database.
 * 
 * @param int $profileId The ID of the profile to reject.
 * @return bool True on success, false on failure.
 */
function rejectTutorProfile($profileId) {
    return updateTutorStatus($profileId, 'rejected');
}


/**
 * Revokes a tutor's verification by setting status back to 'pending'.
 * 
 * @param int $profileId The ID of the profile to revoke.
 * @return bool True on success, false on failure.
 */
function revokeTutorVerification($profileId) {
    return updateTutorStatus($profileId, 'pending');
}


/**
 * Retrieves all tutors regardless of verification status.
 * 
 * @return array|bool List of all tutors or false on error.
 */
function getAllTutors() {
    global $pdo;

    try {
        $sql = "SELECT u.user_id, u.name, u.email, 
                       tp.profile_id, tp.status AS status, tp.hourly_rate, tp.qualification, tp.university_name, tp.transcript_provided
                FROM users u
                INNER JOIN tutorprofiles tp ON u.user_id = tp.user_id
                WHERE u.role = 'tutor'
                ORDER BY FIELD(status, 'pending', 'verified', 'rejected'), u.name ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getAllTutors: " . $e->getMessage());
        return false;
    }
}

/**
 * Aggregates financial data for the platform dashboard.
 * 
 * @return array|bool Financial summary or false on failure.
 */
function getPlatformFinanceSummary() {
    global $pdo;

    try {
        $sql = "SELECT 
                    COALESCE(SUM(platform_fee), 0) as total_revenue,
                    COALESCE(SUM(total_amount), 0) as gross_volume,
                    COALESCE(SUM(CASE WHEN payment_status = 'pending' THEN tutor_earnings ELSE 0 END), 0) as pending_payouts
                FROM payments";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getPlatformFinanceSummary: " . $e->getMessage());
        return false;
    }
}

/**
 * Retrieves recent platform transactions with tutor and student details.
 * 
 * @param int $limit Number of records to return.
 * @return array|bool Transaction list or false on failure.
 */
function getRecentPlatformTransactions($limit = 10) {
    global $pdo;

    try {
        $sql = "SELECT 
                    p.payment_id, 
                    p.booking_id, 
                    p.total_amount, 
                    p.platform_fee, 
                    p.payment_status,
                    u_tutor.name as tutor_name,
                    u_student.name as student_name
                FROM payments p
                JOIN bookings b ON p.booking_id = b.booking_id
                JOIN tutorprofiles tp ON b.tutor_id = tp.profile_id
                JOIN users u_tutor ON tp.user_id = u_tutor.user_id
                JOIN users u_student ON b.student_id = u_student.user_id
                ORDER BY p.payment_id DESC
                LIMIT :limit";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getRecentPlatformTransactions: " . $e->getMessage());
        return false;
    }
}
?>
