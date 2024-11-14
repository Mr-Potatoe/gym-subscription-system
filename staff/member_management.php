<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';

// Display a message if available
$message = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';

// Check if the user has Staff access
checkAccess(2);  // 2 is the role_id for Staff

// Fetch all members from the database
$sql = "SELECT u.user_id, u.first_name, u.last_name, u.contact_info, r.role_name, u.status 
        FROM users u
        JOIN roles r ON u.role_id = r.role_id
        WHERE u.role_id = 3
        ORDER BY u.first_name";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all members

// Handle new member creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $contactInfo = $_POST['contact_info'];
    $status = 'active'; // Default status for new members
    $role_id = 3; // Role ID for members
    $password = $_POST['password'];
    
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert the new member into the database
    $sql = "INSERT INTO users (first_name, last_name, contact_info, role_id, password, status) VALUES (:first_name, :last_name, :contact_info, :role_id, :password, :status)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'first_name' => $firstName,
        'last_name' => $lastName,
        'contact_info' => $contactInfo,
        'role_id' => $role_id,
        'status' => $status,
        'password' => $hashedPassword
    ]);

    // Redirect with success message
    header("Location: member_management.php?msg=Member created successfully");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Members</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1>Manage Members</h1>
    <a href="../admin/dashboard.php">Back to Dashboard</a>

    <?php if ($message): ?>
    <p style="color: green;"><?= $message ?></p>
    <?php endif; ?>

    <!-- New Member Creation Form -->
    <h2>Create New Member</h2>
    <form method="post" action="">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name" required>
        
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name" required>
        
        <label for="contact_info">Contact Info:</label>
        <input type="text" name="contact_info" id="contact_info" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        
        <input type="submit" value="Create Member">
    </form>

    <!-- Existing Members Table -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact Info</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($members as $member): ?>
            <tr>
                <td><?= htmlspecialchars($member['user_id']) ?></td>
                <td><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?></td>
                <td><?= htmlspecialchars($member['contact_info']) ?></td>
                <td><?= htmlspecialchars($member['status']) ?></td>
                <td>
                    <a href="staff_actions/edit_user.php?id=<?= $member['user_id'] ?>">Edit</a>
                    <a href="staff_actions/delete_user.php?id=<?= $member['user_id'] ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
