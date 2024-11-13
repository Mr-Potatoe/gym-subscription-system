<?php
$host = 'localhost';
$dbname = 'db-gym';  // No extra space after the database name
$username = 'root';   // Update with your database username
$password = '';       // Update with your database password
$port = '3307';       // Port, if necessary

try {
    // Fixed the issue by removing the space after the database name
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
