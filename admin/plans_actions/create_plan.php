<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';

// Check if the user has admin access
checkAccess(1);  // 1 is the role_id for Admin

// Handle form submission to create a new plan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $planName = $_POST['plan_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];

    // Validation
    if (empty($planName) || empty($description) || empty($price) || empty($duration)) {
        $error = "All fields are required.";
    } else {
        // Insert new plan into the database
        $sql = "INSERT INTO membership_plans (plan_name, description, price, duration) 
                VALUES (:plan_name, :description, :price, :duration)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'plan_name' => $planName,
            'description' => $description,
            'price' => $price,
            'duration' => $duration
        ]);
        
        $success = "Plan created successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Membership Plan</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1>Create New Membership Plan</h1>

    <form action="create_plan.php" method="POST">
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p style="color: green;"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <label for="plan_name">Plan Name:</label>
        <input type="text" name="plan_name" id="plan_name" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" required>

        <label for="duration">Duration (days):</label>
        <input type="number" name="duration" id="duration" required>

        <button type="submit">Create Plan</button>
    </form>

    <a href="../dashboard.php">Back to Dashboard</a>
</body>
</html>
