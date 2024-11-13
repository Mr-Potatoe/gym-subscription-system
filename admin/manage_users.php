<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';

// Display a message if available
$message = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';


// Check if the user has admin access
checkAccess(1);  // 1 is the role_id for Admin

// Fetch all users from the database
$sql = "SELECT user_id, first_name, last_name, contact_info, role_id, status FROM users";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1>Manage Users</h1>
    <a href="dashboard.php">Back to Dashboard</a>

    <?php if ($message): ?>
    <p style="color: green;"><?= $message ?></p>
<?php endif; ?>

    
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact Info</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['user_id']) ?></td>
                <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                <td><?= htmlspecialchars($user['contact_info']) ?></td>
                <td><?= htmlspecialchars($user['role_id']) ?></td>
                <td><?= htmlspecialchars($user['status']) ?></td>
                <td>
                    <a href="user_actions/edit_user.php?id=<?= $user['user_id'] ?>">Edit</a>
                    <a href="user_actions/delete_user.php?id=<?= $user['user_id'] ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
