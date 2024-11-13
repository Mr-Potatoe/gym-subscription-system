<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');  // Redirect if not logged in
    exit;
}

// Get the user's ID from the session
$userId = $_SESSION['user_id'];

// Fetch the user's data for displaying in the form
$sql = "SELECT first_name, last_name, contact_info FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}

// Handle form submission and update the profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $contactInfo = $_POST['contact_info'];
    $password = $_POST['password'];
    
    // Only update the password if it's not empty
    if (!empty($password)) {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, contact_info = :contact_info, password = :password WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'contact_info' => $contactInfo,
            'password' => $hashedPassword,
            'user_id' => $userId
        ]);
    } else {
        // Update without changing the password
        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, contact_info = :contact_info WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'contact_info' => $contactInfo,
            'user_id' => $userId
        ]);
    }

    echo "Profile updated successfully!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1>Update Your Profile</h1>

    <form method="POST">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required><br>
        
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required><br>
        
        <label for="contact_info">Contact Info:</label>
        <input type="email" name="contact_info" id="contact_info" value="<?= htmlspecialchars($user['contact_info']) ?>" required><br>
        
        <label for="password">New Password (Leave empty to keep current password):</label>
        <input type="password" name="password" id="password"><br>

        <button type="submit">Update Profile</button>
    </form>

    <br>
    <a href="dashboard.php">Back to Profile</a>

</body>
</html>
