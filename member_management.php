<?php
include 'db.php';
include 'access_control.php';

// Check if the user has staff access
checkAccess(2);  // 2 is the role_id for Staff

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Management</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1>Member Management</h1>
    <p>Manage memberships, process payments, and verify member information.</p>
    
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="view_members.php">View Members</a>
        <a href="process_payments.php">Process Payments</a>
        <a href="logout.php">Logout</a>
    </nav>

    <!-- Example content for staff functionality -->
    <section>
        <h2>Current Members</h2>
        <!-- You could include a table of members and payment statuses here -->
    </section>
</body>
</html>
