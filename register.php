<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $contactInfo = $_POST['contact_info'];
    $password = $_POST['password'];
    
    // Default role_id for members
    $roleId = 3; 

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    $sql = "INSERT INTO users (first_name, last_name, contact_info, role_id, password, status) 
            VALUES (:first_name, :last_name, :contact_info, :role_id, :password, 'active')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'first_name' => $firstName, 
        'last_name' => $lastName, 
        'contact_info' => $contactInfo, 
        'role_id' => $roleId,
        'password' => $hashedPassword
    ]);
    
    echo "User registered successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="login-register.css">
</head>
<body>
    <div class="form-container">
    
    <form method="POST">
    <h1>Register</h1>
        <input type="text" name="first_name" placeholder="First Name" required><br>
        <input type="text" name="last_name" placeholder="Last Name" required><br>
        <input type="email" name="contact_info" placeholder="Contact Info" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Register</button>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
    </div>
</body>
</html>
