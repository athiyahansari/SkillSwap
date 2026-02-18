<?php
/**
 * Database Connection Configuration
 * This file is included in other pages to establish a connection to the database.
 */

$host = 'localhost';
$dbname = 'skillswap';
$username = 'root';
$password = '';

try {
    // Create a new PDO instance
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);

    // Set error mode to exception to catch any connection issues
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Only show confirmation if the file is accessed directly
    if (basename($_SERVER['PHP_SELF']) == 'db.php') {
        echo "Database connected successfully!";
    }

} catch (PDOException $e) {
    // If connection fails, display a relevant error message and stop execution
    die("Database connection failed: " . $e->getMessage());
}
?>
