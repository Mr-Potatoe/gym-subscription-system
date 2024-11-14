<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';

// Check if the user has Staff access
checkAccess(2);  // 2 is the role_id for Staff

// Get the user ID from the URL
$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the user information
$sql = "SELECT user_id, first_name, last_name, contact_info, status FROM users WHERE user_id = :user_id AND role_id = 3";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $contactInfo = $_POST['contact_info'];
    $status = $_POST['status'];

    // Update user information in the database
    $updateSql = "UPDATE users SET first_name = :first_name, last_name = :last_name, contact_info = :contact_info, status = :status WHERE user_id = :user_id AND role_id = 3";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([
        'first_name' => $firstName,
        'last_name' => $lastName,
        'contact_info' => $contactInfo,
        'status' => $status,
        'user_id' => $userId
    ]);

    // Redirect back to Manage Members page with a success message
    header("Location: ../member_management.php?msg=User updated successfully");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Member</title>
</head>
<body>
    <h1>Edit Member</h1>
    <a href="../member_management.php">Back to Manage Members</a>

    <form method="POST">
        <label>First Name: <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required></label><br>
        <label>Last Name: <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required></label><br>
        <label>Contact Info: <input type="text" name="contact_info" value="<?= htmlspecialchars($user['contact_info']) ?>" required></label><br>
        <label>Status:
            <select name="status">
                <option value="active" <?= $user['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $user['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </label><br>
        <button type="submit">Update Member</button>
    </form>
</body>
</html>
