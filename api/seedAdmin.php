
<?php
require_once "../functions/db.php";


$email = "admin@skillswap.com";
$plainPassword = "admin123";
$role = "admin";

// hash password 
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// insert admin - 1 admin already added

$stmt = $pdo->prepare(
  "INSERT INTO users (email, password, role)
   VALUES (?, ?, ?)"
);

$stmt->execute([$email, $hashedPassword, $role]);

echo "Admin user created successfully"; 

