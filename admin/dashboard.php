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
    <link rel="stylesheet" href="assets/css/styles.css">
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
            <a href="member_management.php">Manage Members</a>
        
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
