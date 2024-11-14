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

        /* Links */
        a {
            color: #a9a9b8;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin: 1rem;
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

        /* Form Styling */
        form {
            background-color: #2e2e3a;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 6px 6px 12px #141424, -6px -6px 12px #282844;
            width: 100%;
            max-width: 600px;
            margin-top: 2rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        input, textarea {
            width: 100%;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #1e1e2f;
            color: #fff;
            border: 1px solid #444;
            border-radius: 8px;
            box-shadow: inset 4px 4px 8px #141424, inset -4px -4px 8px #282844;
            font-size: 1rem;
        }

        button {
            padding: 1rem 2rem;
            background-color: #444;
            color: #fff;
            font-size: 1.1rem;
            border: none;
            border-radius: 8px;
            box-shadow: 4px 4px 8px #141424, -4px -4px 8px #282844;
            cursor: pointer;
            transition: background-color 0.2s, box-shadow 0.2s;
        }

        button:hover {
            background-color: #333;
            box-shadow: 4px 4px 8px #141424, -4px -4px 8px #282844;
        }
    </style>
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

