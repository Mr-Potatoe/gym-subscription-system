<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Redirect if not logged in
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $contactInfo = $_POST['contact_info'];
    
    // Update user profile
    $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, contact_info = :contact_info WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['first_name' => $firstName, 'last_name' => $lastName, 'contact_info' => $contactInfo, 'user_id' => $userId]);
    
    echo "Profile updated successfully!";
}

$sql = "SELECT * FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<form method="POST">
    <input type="text" name="first_name" value="<?= $user['first_name'] ?>" placeholder="First Name" required><br>
    <input type="text" name="last_name" value="<?= $user['last_name'] ?>" placeholder="Last Name" required><br>
    <input type="email" name="contact_info" value="<?= $user['contact_info'] ?>" placeholder="Contact Info" required><br>
    <button type="submit">Update Profile</button>
</form>
