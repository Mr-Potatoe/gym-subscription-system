<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Redirect if not logged in
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch membership details and verify payment status
$sql = "SELECT DISTINCT
            m.plan_name, 
            m.description, 
            m.price, 
            m.duration, 
            u.membership_start_date, 
            u.membership_end_date
        FROM memberships u
        JOIN membership_plans m ON u.plan_id = m.plan_id
        WHERE u.user_id = :user_id 
        AND u.status = 'approved' 
        ORDER BY u.membership_start_date DESC"; // Get the most recent membership

$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $userId]);
$memberships = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all memberships

// Debugging: Check if memberships are being fetched correctly
if (empty($memberships)) {
    echo "You don't have any verified memberships or there was an issue fetching your data.";
    var_dump($stmt->errorInfo());  // Print detailed error information
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Membership</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <h1>Your Membership Details</h1>

    <table border="1">
        <tr>
            <th>Plan Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Duration</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>
        <?php foreach ($memberships as $membership): ?>
        <tr>
            <td><?= htmlspecialchars($membership['plan_name']) ?></td>
            <td><?= htmlspecialchars($membership['description']) ?></td>
            <td>$<?= htmlspecialchars($membership['price']) ?></td>
            <td><?= htmlspecialchars($membership['duration']) ?> days</td>
            <td><?= htmlspecialchars($membership['membership_start_date']) ?></td>
            <td><?= htmlspecialchars($membership['membership_end_date']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="../admin/dashboard.php">Back to Dashboard</a>
</body>

</html>
