<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contactInfo = $_POST['contact_info'];
    $password = $_POST['password']; // Assume password is hashed
    
    $sql = "SELECT * FROM users WHERE contact_info = :contact_info";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['contact_info' => $contactInfo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role_id'] = $user['role_id'];
        header('Location: admin/dashboard.php');  // Redirect to dashboard after successful login
        exit;
    } else {
        echo "Invalid login credentials!";
    }
}
?>

<form method="POST">
    <input type="email" name="contact_info" placeholder="Contact Info" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
