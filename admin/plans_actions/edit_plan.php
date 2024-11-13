<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';

// Check if the user has admin access
checkAccess(1);  // 1 is the role_id for Admin

// Get the plan ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../manage_plans.php?msg=Invalid+plan+ID");
    exit;
}

$planId = $_GET['id'];

// Fetch plan information from the database
$sql = "SELECT * FROM membership_plans WHERE plan_id = :plan_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['plan_id' => $planId]);
$plan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$plan) {
    header("Location: ../manage_plans.php?msg=Plan+not+found");
    exit;
}

// Update plan details if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $planName = $_POST['plan_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];

    $updateSql = "UPDATE membership_plans SET plan_name = :plan_name, description = :description, price = :price, duration = :duration WHERE plan_id = :plan_id";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([
        'plan_name' => $planName,
        'description' => $description,
        'price' => $price,
        'duration' => $duration,
        'plan_id' => $planId
    ]);

    header("Location: ../manage_plans.php?msg=Plan+updated+successfully");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Membership Plan</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1>Edit Membership Plan</h1>
    <a href="../manage_plans.php">Back to Manage Plans</a>

    <form method="POST">
        <label for="plan_name">Plan Name:</label>
        <input type="text" id="plan_name" name="plan_name" value="<?= htmlspecialchars($plan['plan_name']) ?>" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($plan['description']) ?></textarea><br>

        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($plan['price']) ?>" required><br>

        <label for="duration">Duration (days):</label>
        <input type="number" id="duration" name="duration" value="<?= htmlspecialchars($plan['duration']) ?>" required><br>

        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
