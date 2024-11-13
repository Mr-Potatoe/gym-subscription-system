<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';

// Check if the user has admin access
checkAccess(1);  // 1 is the role_id for Admin

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome to the admin section. Here you can manage users, membership plans, and payments.</p>
    
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_plans.php">Manage Membership Plans</a>
        <a href="verify_payments.php">Verify Payments</a>
        <a href="../logout.php">Logout</a>
    </nav>
</body>
</html>
