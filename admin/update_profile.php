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

        input[type="text"], input[type="email"], input[type="password"] {
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

        /* Navigation Link */
        a {
            color: #a9a9b8;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 1.5rem;
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
    </style>
</head>
<body>

<h1>Update Your Profile</h1>

<form method="POST">
    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>

    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>

    <label for="contact_info">Contact Info:</label>
    <input type="email" name="contact_info" id="contact_info" value="<?= htmlspecialchars($user['contact_info']) ?>" required>

    <label for="password">New Password (Leave empty to keep current password):</label>
    <input type="password" name="password" id="password">

    <button type="submit">Update Profile</button>
</form>

<a href="dashboard.php">Back to Profile</a>

</body>
</html>
