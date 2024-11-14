<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';

// Display a message if available
$message = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';

// Check if the user has admin access
checkAccess(1);  // 1 is the role_id for Admin

// Fetch all users with their roles from the database
$sql = "SELECT u.user_id, u.first_name, u.last_name, u.contact_info, r.role_name, u.status 
        FROM users u
        JOIN roles r ON u.role_id = r.role_id
        ORDER BY r.role_name";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize $categorizedUsers as an empty array
$categorizedUsers = [];

// Categorize users by role if users were fetched
if ($users) {
    foreach ($users as $user) {
        $role = $user['role_name'];
        if (!isset($categorizedUsers[$role])) {
            $categorizedUsers[$role] = [];
        }
        $categorizedUsers[$role][] = $user;
    }
}

// Fetch roles from the database to populate the dropdown
$rolesQuery = "SELECT role_id, role_name FROM roles";
$rolesStmt = $pdo->prepare($rolesQuery);
$rolesStmt->execute();
$roles = $rolesStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle new user creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $contactInfo = $_POST['contact_info'];
    $role_id = $_POST['role_id'];  // Use the role selected in the form
    $status = 'active'; // Set default status for new user
    $password = $_POST['password'];
    
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

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

    header("Location: manage_users.php?msg=User created successfully");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
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

        h1, h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 1rem;
        }

        /* Navigation Links */
        a {
            color: #a9a9b8;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 1rem;
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
            margin-bottom: 2rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #a9a9b8;
        }

        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            border: none;
            background-color: #1e1e2f;
            color: #fff;
            box-shadow: inset 4px 4px 8px #141424, inset -4px -4px 8px #282844;
        }

        input[type="submit"] {
            width: 100%;
            padding: 0.75rem;
            font-weight: bold;
            border-radius: 8px;
            background-color: #1e1e2f;
            color: #a9a9b8;
            box-shadow: 6px 6px 12px #141424, -6px -6px 12px #282844;
            transition: color 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            color: #fff;
            box-shadow: 4px 4px 8px #141424, -4px -4px 8px #282844;
        }

        /* Table Styling */
        table {
            width: 100%;
            max-width: 700px;
            background-color: #1e1e2f;
            margin: 1rem 0;
            border-collapse: collapse;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 9px 9px 16px #141424, -9px -9px 16px #282844;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            color: #a9a9b8;
        }

        th {
            background-color: #2a2a3f;
        }

        tr {
            border-bottom: 1px solid #282844;
        }

        tr:last-child {
            border-bottom: none;
        }

        /* Actions Links */
        td a {
            color: #a9a9b8;
            font-weight: bold;
            padding: 0.3rem 0.75rem;
            border-radius: 6px;
            background-color: #1e1e2f;
            box-shadow: 3px 3px 6px #141424, -3px -3px 6px #282844;
            transition: color 0.2s, box-shadow 0.2s;
        }

        td a:hover {
            color: #fff;
            box-shadow: 2px 2px 4px #141424, -2px -2px 4px #282844;
        }
    </style>
</head>
<body>

<h1>Manage Users</h1>
<a href="dashboard.php">Back to Dashboard</a>

<?php if ($message): ?>
<p style="color: green;"><?= $message ?></p>
<?php endif; ?>

<!-- New User Creation Form -->
<h2>Create New User</h2>
<form method="post" action="">
    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" id="first_name" required>
    
    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" id="last_name" required>
    
    <label for="contact_info">Contact Info:</label>
    <input type="text" name="contact_info" id="contact_info" required>

    <label for="role_id">Role:</label>
    <select name="role_id" id="role_id" required>
        <?php foreach ($roles as $role): ?>
            <option value="<?= htmlspecialchars($role['role_id']) ?>">
                <?= htmlspecialchars($role['role_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    
    <input type="submit" value="Create User">
</form>

<!-- Existing Users Table -->
<?php if (!empty($categorizedUsers)): ?>
    <?php foreach ($categorizedUsers as $role => $users): ?>
        <h2><?= htmlspecialchars($role) ?>s</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact Info</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['user_id']) ?></td>
                    <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                    <td><?= htmlspecialchars($user['contact_info']) ?></td>
                    <td><?= htmlspecialchars($user['status']) ?></td>
                    <td>
                        <a href="user_actions/edit_user.php?id=<?= $user['user_id'] ?>">Edit</a>
                        <a href="user_actions/delete_user.php?id=<?= $user['user_id'] ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
<?php else: ?>
    <p>No users found.</p>
<?php endif; ?>

</body>
</html>

