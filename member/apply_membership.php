<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');  // Redirect if not logged in
    exit;
}

$userId = $_SESSION['user_id'];

// Check if the user already has a pending membership application
$sql = "SELECT * FROM memberships WHERE user_id = :user_id AND status = 'pending'";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $userId]);
$existingMembership = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch available membership plans
$sql = "SELECT * FROM membership_plans";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle membership application
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$existingMembership) {
    $planId = $_POST['plan_id'];

    // Fetch selected plan to get the duration and price
    $sql = "SELECT duration, price FROM membership_plans WHERE plan_id = :plan_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['plan_id' => $planId]);
    $plan = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($plan) {
        // Calculate membership_end_date
        $membershipEndDate = date('Y-m-d H:i:s', strtotime("+{$plan['duration']} days"));

        // Insert new membership with status 'pending'
        $sql = "INSERT INTO memberships (user_id, plan_id, membership_start_date, membership_end_date, status) 
                VALUES (:user_id, :plan_id, CURRENT_TIMESTAMP, :membership_end_date, 'pending')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'plan_id' => $planId,
            'membership_end_date' => $membershipEndDate
        ]);

        // Insert corresponding pending payment record with the payment amount
        $sql = "INSERT INTO payments (user_id, plan_id, payment_amount, payment_status) 
                VALUES (:user_id, :plan_id, :payment_amount, 'pending')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'plan_id' => $planId,  // The selected plan ID
            'payment_amount' => $plan['price']  // The price of the selected membership plan
        ]);

        // Redirect to avoid re-submission
        header('Location: apply_membership.php?status=submitted');
        exit;  // Make sure no further code executes after redirection
    } else {
        echo "Invalid plan selected.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply for Membership</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Apply for Membership</h1>

    <!-- Show success message if form was successfully submitted -->
    <?php if (isset($_GET['status']) && $_GET['status'] == 'submitted'): ?>
        <p style="color: green;">Membership successfully applied! Please upload payment proof for verification.</p>
    <?php endif; ?>

    <!-- Show message if the membership is already pending -->
    <?php if ($existingMembership): ?>
        <p style="color: orange;">You already have a pending membership application. Please wait until it is processed.</p>
    <?php else: ?>
        <!-- Display the membership application form if no pending membership exists -->
        <form action="apply_membership.php" method="POST">
            <label for="plan_id">Select a Membership Plan:</label>
            <select name="plan_id" id="plan_id" required>
                <?php foreach ($plans as $plan): ?>
                    <option value="<?= $plan['plan_id'] ?>"><?= htmlspecialchars($plan['plan_name']) ?> - $<?= htmlspecialchars($plan['price']) ?> (<?= htmlspecialchars($plan['duration']) ?> days)</option>
                <?php endforeach; ?>
            </select>

            <br>
            <!-- Disable the apply button if membership is already pending -->
            <button type="submit" <?= $existingMembership ? 'disabled' : '' ?>>Apply for Membership</button>
        </form>
    <?php endif; ?>

    <nav>
        <a href="../admin/dashboard.php">Back to Profile</a>
    </nav>
</body>
</html>
