<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';


// Check if the user has admin access
checkAccess(1);  // 1 is the role_id for Admin

// Fetch all membership plans
$sql = "SELECT * FROM membership_plans";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Membership Plans</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <h1>Manage Membership Plans</h1>
    <a href="dashboard.php">Back to Dashboard</a>

    <a href="plans_actions/create_plan.php">Create New Plan</a>


    <table border="1">
        <tr>
            <th>Plan ID</th>
            <th>Plan Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Duration (days)</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($plans as $plan): ?>
            <tr>
                <td><?= htmlspecialchars($plan['plan_id']) ?></td>
                <td><?= htmlspecialchars($plan['plan_name']) ?></td>
                <td><?= htmlspecialchars($plan['description']) ?></td>
                <td><?= htmlspecialchars($plan['price']) ?></td>
                <td><?= htmlspecialchars($plan['duration']) ?></td>
                <td>
                    <a href="plans_actions/edit_plan.php?id=<?= $plan['plan_id'] ?>">Edit</a>
                    <a href="plans_actions/delete_plan.php?id=<?= $plan['plan_id'] ?>"
                        onclick="return confirm('Are you sure you want to delete this plan?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>