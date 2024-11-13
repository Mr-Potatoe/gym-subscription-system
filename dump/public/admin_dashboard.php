 
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once('../config/database.php');

$stmt = $pdo->query("SELECT * FROM payments WHERE status = 'pending'");
$payments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <h2>Pending Payments</h2>
        <?php foreach ($payments as $payment): ?>
            <div>
                <p>Payment ID: <?php echo $payment['payment_id']; ?> - User ID: <?php echo $payment['user_id']; ?></p>
                <form action="verify_payment.php" method="POST">
                    <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id']; ?>">
                    <button type="submit">Verify Payment</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
