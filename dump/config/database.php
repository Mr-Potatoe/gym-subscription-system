 
<?php
$host = 'localhost';
$dbname = 'gym_subscription';
$username = 'root'; // Update with your database username
$password = ''; // Update with your database password
$port = '3307';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname ;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
