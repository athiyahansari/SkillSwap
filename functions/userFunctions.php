<?php
/**
 * User Model Functions
 * Handles user creation for different roles.
 * Requires `db.php` for database access.
 */

/**
 * Checks if an email already exists in the users table.
 * 
 * @param string $email
 * @return bool True if exists, false otherwise.
 */
function emailExists($email) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return (int)$stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error check if email exists: " . $e->getMessage());
        return false;
    }
}


/**
 * Create a new learner account.
 * 
 * @param array $data Expected keys: 'first_name', 'last_name', 'email', 'password'
 * @return string|bool The ID of the created user or false on failure.
 */
function createLearner($data) {
    global $pdo;

    try {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $fullName = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        
        // Note: phone, education_level, subjects_interest, timezone are now in the 'users' schema.
        $sql = "INSERT INTO users (name, email, password, role, phone, timezone, education_level, subjects_interest, otp_code, is_verified) 
                VALUES (:name, :email, :password, 'learner', :phone, :timezone, :education_level, :subjects_interest, :otp_code, 0)";
        
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([
            ':name'              => $fullName,
            ':email'             => $data['email'],
            ':password'          => $hashedPassword,
            ':phone'             => $data['phone'] ?? null,
            ':timezone'          => $data['timezone'] ?? null,
            ':education_level'   => $data['education_level'] ?? null,
            ':subjects_interest' => $data['subjects'] ?? null,
            ':otp_code'          => $data['otp_code'] ?? null
        ]);

        return $success ? $pdo->lastInsertId() : false;
    } catch (PDOException $e) {
        error_log("Error creating learner: " . $e->getMessage());
        return false;
    }
}

/**
 * Create a new tutor account and their profile.
 * 
 * @param array $data Expected keys: 'first_name', 'last_name', 'email', 'password', 'subject', 'rate'
 * @return string|bool The ID of the created user or false on failure.
 */
function createTutor($data) {
    global $pdo;

    try {
        $pdo->beginTransaction();

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $fullName = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        
        // 1. Insert into users table
        $sqlUser = "INSERT INTO users (name, email, password, role, phone, timezone, otp_code, is_verified) 
                    VALUES (:name, :email, :password, 'tutor', :phone, :timezone, :otp_code, 0)";
        
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute([
            ':name'     => $fullName,
            ':email'    => $data['email'],
            ':password' => $hashedPassword,
            ':phone'    => $data['phone'] ?? null,
            ':timezone' => $data['timezone'] ?? null,
            ':otp_code' => $data['otp_code'] ?? null
        ]);

        $userId = $pdo->lastInsertId();

        // 2. Insert into tutorprofiles table
        $sqlProfile = "INSERT INTO tutorprofiles (user_id, hourly_rate, bio, qualification, university_name, payout_method, payout_details, status, transcript_provided) 
                       VALUES (:user_id, :hourly_rate, :bio, :qualification, :university_name, :payout_method, :payout_details, 'pending', 1)";
        
        $stmtProfile = $pdo->prepare($sqlProfile);
        $stmtProfile->execute([
            ':user_id'         => $userId,
            ':hourly_rate'     => $data['rate'] ?? 0,
            ':bio'             => $data['bio'] ?? null,
            ':qualification'   => $data['qualification'] ?? null,
            ':university_name' => $data['university_name'] ?? null,
            ':payout_method'   => $data['payout_method'] ?? null,
            ':payout_details'  => $data['payout_details'] ?? null
        ]);

        $profileId = $pdo->lastInsertId();

        // 3. Handle Subject mapping (tutorsubjects)
        if (!empty($data['subject'])) {
            // Get subject_id from subjects table
            $sqlSubj = "SELECT subject_id FROM subjects WHERE subject_name = :subject_name LIMIT 1";
            $stmtSubj = $pdo->prepare($sqlSubj);
            $stmtSubj->execute([':subject_name' => $data['subject']]);
            $subject = $stmtSubj->fetch(PDO::FETCH_ASSOC);

            if ($subject) {
                $sqlTutorSubj = "INSERT INTO tutorsubjects (profile_id, subject_id) VALUES (:profile_id, :subject_id)";
                $stmtTutorSubj = $pdo->prepare($sqlTutorSubj);
                $stmtTutorSubj->execute([
                    ':profile_id' => $profileId,
                    ':subject_id' => $subject['subject_id']
                ]);
            }
        }

        $pdo->commit();
        return $userId;
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Error creating tutor: " . $e->getMessage());
        return false;
    }
}
?>
