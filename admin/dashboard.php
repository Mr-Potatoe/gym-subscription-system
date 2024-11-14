<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');  // Redirect if not logged in
    exit;
}

// Get the user's role and ID from the session
$userId = $_SESSION['user_id'];
$roleId = $_SESSION['role_id'];

// Fetch user data for display
$sql = "SELECT first_name, last_name, role_name FROM users 
        JOIN roles ON users.role_id = roles.role_id 
        WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}

// Display different dashboard content based on user role
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Dark Background */
        body {
            background-color: #1e1e2f;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
        }

        /* Heading Styling */
        h1, h2, h3 {
            text-align: center;
            margin: 1rem 0;
            color: #fff;
        }

        /* Navigation */
        nav {
            background-color: #1e1e2f;
            padding: 1rem;
            border-radius: 15px;
            margin: 1rem 0;
            box-shadow: 9px 9px 16px #141424, -9px -9px 16px #282844;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        nav a {
            color: #a9a9b8;
            text-decoration: none;
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #fff;
        }

        /* Section Styling */
        section {
            background-color: #1e1e2f;
            padding: 2rem;
            border-radius: 15px;
            width: 80%;
            max-width: 600px;
            box-shadow: 9px 9px 16px #141424, -9px -9px 16px #282844;
            text-align: center;
            margin-top: 1rem;
        }

        /* Dashboard Links */
        section a {
            display: inline-block;
            color: #a9a9b8;
            text-decoration: none;
            margin: 1rem;
            font-weight: bold;
            padding: 0.75rem 1.5rem;
            background-color: #1e1e2f;
            border-radius: 10px;
            box-shadow: 6px 6px 12px #141424, -6px -6px 12px #282844;
            transition: box-shadow 0.2s ease-in-out, color 0.2s ease-in-out;
        }

        section a:hover {
            color: #fff;
            box-shadow: 4px 4px 8px #141424, -4px -4px 8px #282844;
        }

        section a:active {
            box-shadow: inset 6px 6px 12px #141424, inset -6px -6px 12px #282844;
        }
    </style>
</head>
<body>

<h1>Welcome, <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>!</h1>
<h2>Your Role: <?= htmlspecialchars($user['role_name']) ?></h2>

<nav>
    <a href="update_profile.php">Profile</a>
    <a href="../logout.php">Logout</a>
</nav>

<section>
    <?php if ($roleId == 1): // Admin Dashboard ?>
        <h3>Admin Dashboard</h3>
        <p>Manage users, review membership plans, and oversee payments.</p>
        <a href="admin_dashboard.php">Go to Admin Section</a>
    
    <?php elseif ($roleId == 2): // Staff Dashboard ?>
        <h3>Staff Dashboard</h3>
        <p>Manage memberships and process payments.</p>
        <a href="../staff/member_management.php">Manage Members</a>
    
    <?php elseif ($roleId == 3): // Member Dashboard ?>
        <h3>Member Dashboard</h3>
        <p>View and manage your membership details.</p>
        <a href="../member/view_membership.php">View Membership</a>
        <a href="../member/upload_payment.php">Upload Payment Proof</a>
        <a href="../member/apply_membership.php">Apply Membership</a>
    
    <?php else: ?>
        <p>Invalid role. Please contact support.</p>
    <?php endif; ?>
</section>

</body>
</html>
