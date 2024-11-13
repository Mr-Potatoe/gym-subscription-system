<?php
include 'db.php';
include 'access_control.php';

// Check if the user has member access
checkAccess(3);  // 3 is the role_id for Member

// Assuming $userId is the member's ID, fetch their membership details
$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM membership_plans 
        JOIN payments ON membership_plans.plan_id = payments.plan_id 
        WHERE payments.user_id = :user_id ORDER BY payments.payment_date DESC LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $userId]);
$membership = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Membership</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1>My Membership</h1>
    
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="upload_payment.php">Upload Payment Proof</a>
        <a href="logout.php">Logout</a>
    </nav>

    <?php if ($membership): ?>
        <h2>Membership Details</h2>
        <p>Plan: <?= htmlspecialchars($membership['plan_name']) ?></p>
        <p>Price: $<?= htmlspecialchars($membership['price']) ?></p>
        <p>Duration: <?= htmlspecialchars($membership['duration']) ?> days</p>
        <p>Status: <?= htmlspecialchars($membership['payment_status']) ?></p>
        <p>Last Payment Date: <?= htmlspecialchars($membership['payment_date']) ?></p>
    <?php else: ?>
        <p>No active membership found. Please contact support.</p>
    <?php endif; ?>
</body>
</html>
