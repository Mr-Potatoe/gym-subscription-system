<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include 'roles.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $contactInfo = $_POST['contact_info'];
    $roleId = $_POST['role_id'];
    $password = $_POST['password'];
    
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

<form method="POST">
    <input type="text" name="first_name" placeholder="First Name" required><br>
    <input type="text" name="last_name" placeholder="Last Name" required><br>
    <input type="email" name="contact_info" placeholder="Contact Info" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <select name="role_id">
        <?php
        $roles = getAllRoles();
        foreach ($roles as $role) {
            echo "<option value='{$role['role_id']}'>{$role['role_name']}</option>";
        }
        ?>
    </select><br>
    <button type="submit">Register</button>
</form>
