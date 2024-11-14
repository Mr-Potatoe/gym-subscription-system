<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';

// Check if the user has admin access
checkAccess(1);  // 1 is the role_id for Admin

// Get the user ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../manage_users.php?msg=Invalid+user+ID");
    exit;
}

$userId = $_GET['id'];

// Fetch user information from the database
$sql = "SELECT * FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: ../manage_users.php?msg=User+not+found");
    exit;
}

// Update user details if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $contactInfo = $_POST['contact_info'];
    $roleId = $_POST['role_id'];
    $status = $_POST['status'];

    $updateSql = "UPDATE users SET first_name = :first_name, last_name = :last_name, contact_info = :contact_info, role_id = :role_id, status = :status WHERE user_id = :user_id";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([
        'first_name' => $firstName,
        'last_name' => $lastName,
        'contact_info' => $contactInfo,
        'role_id' => $roleId,
        'status' => $status,
        'user_id' => $userId
    ]);

    header("Location: ../manage_users.php?msg=User+updated+successfully");
    exit;
}

// Fetch all roles for the role dropdown
$rolesSql = "SELECT * FROM roles";
$rolesStmt = $pdo->prepare($rolesSql);
$rolesStmt->execute();
$roles = $rolesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Body Styling */
        body {
            background-color: #1e1e2f;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
        }

        h1 {
            color: #fff;
            text-align: center;
            margin-bottom: 1rem;
        }

        /* Navigation Link */
        a {
            color: #a9a9b8;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 1.5rem;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            background-color: #1e1e2f;
            box-shadow: 6px 6px 12px #141424, -6px -6px 12px #282844;
            transition: color 0.2s, box-shadow 0.2s;
        }

        a:hover {
            color: #fff;
            box-shadow: 4px 4px 8px #141424, -4px -4px 8px #282844;
        }

        /* Form Styling */
        form {
            background-color: #1e1e2f;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 9px 9px 16px #141424, -9px -9px 16px #282844;
            max-width: 500px;
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #a9a9b8;
        }

        input[type="text"], input[type="email"], select {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            border: none;
            background-color: #1e1e2f;
            color: #fff;
            box-shadow: inset 4px 4px 8px #141424, inset -4px -4px 8px #282844;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            font-weight: bold;
            border-radius: 8px;
            background-color: #1e1e2f;
            color: #a9a9b8;
            box-shadow: 6px 6px 12px #141424, -6px -6px 12px #282844;
            transition: color 0.2s, box-shadow 0.2s;
            cursor: pointer;
            border: none;
        }

        button:hover {
            color: #fff;
            box-shadow: 4px 4px 8px #141424, -4px -4px 8px #282844;
        }
    </style>
</head>
<body>

<h1>Edit User</h1>
<a href="../manage_users.php">Back to Manage Users</a>

<form method="POST">
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>

    <label for="contact_info">Contact Info:</label>
    <input type="email" id="contact_info" name="contact_info" value="<?= htmlspecialchars($user['contact_info']) ?>" required>

    <label for="role_id">Role:</label>
    <select id="role_id" name="role_id">
        <?php foreach ($roles as $role): ?>
            <option value="<?= $role['role_id'] ?>" <?= $role['role_id'] == $user['role_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($role['role_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="status">Status:</label>
    <select id="status" name="status">
        <option value="active" <?= $user['status'] == 'active' ? 'selected' : '' ?>>Active</option>
        <option value="inactive" <?= $user['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
    </select>

    <button type="submit">Save Changes</button>
</form>

</body>
</html>

