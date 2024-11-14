<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Fetch membership plans to display on the home page
$sql = "SELECT plan_name, price, duration, description FROM membership_plans ORDER BY price ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gym Subscription System</title>
    <link rel="stylesheet" href="index.css">
 
</head>
<body>

<header>
    <h1>Welcome to Our Gym!</h1>
    <nav>
        <?php if ($isLoggedIn): ?>
            <a href="admin/dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>

<section>
    <h2>About Us</h2>
    <p>Welcome to our gym subscription system! Whether you're just starting your fitness journey or you're a seasoned athlete, we have something for everyone. Check out our membership plans below to get started.</p>
</section>

<section>
    <h2>Membership Plans</h2>
    <table>
        <tr>
            <th>Plan Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Duration</th>
        </tr>
        <?php foreach ($plans as $plan): ?>
        <tr>
            <td><?= htmlspecialchars($plan['plan_name']) ?></td>
            <td><?= htmlspecialchars($plan['description']) ?></td>
            <td>$<?= htmlspecialchars($plan['price']) ?></td>
            <td><?= htmlspecialchars($plan['duration']) ?> days</td>
        </tr>
        <?php endforeach; ?>
    </table>
</section>

<footer>
    <p>&copy; <?= date("Y") ?> Gym Subscription System. All rights reserved.</p>
</footer>

</body>
</html>

