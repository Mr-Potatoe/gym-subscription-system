<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';

// Check if the user has admin access
checkAccess(1);  // 1 is the role_id for Admin

// Fetch payments based on their status categories
$sqlPending = "SELECT * FROM payments WHERE payment_status = 'pending'";
$sqlVerified = "SELECT * FROM payments WHERE payment_status = 'verified'";
$sqlFailed = "SELECT * FROM payments WHERE payment_status = 'failed'";

$stmtPending = $pdo->prepare($sqlPending);
$stmtPending->execute();
$pendingPayments = $stmtPending->fetchAll(PDO::FETCH_ASSOC);

$stmtVerified = $pdo->prepare($sqlVerified);
$stmtVerified->execute();
$verifiedPayments = $stmtVerified->fetchAll(PDO::FETCH_ASSOC);

$stmtFailed = $pdo->prepare($sqlFailed);
$stmtFailed->execute();
$failedPayments = $stmtFailed->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Payments</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .payment-category {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .payment-category h2 {
            margin-top: 0;
        }
        .pending { background-color: #f9f9f9; }
        .verified { background-color: #e6ffe6; }
        .failed { background-color: #ffe6e6; }
        .status-actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <h1>Verify Payments</h1>
    <a href="dashboard.php">Back to Dashboard</a>

    <!-- Pending Payments -->
    <div class="payment-category pending">
        <h2>Pending Payments</h2>
        <table border="1">
            <tr>
                <th>Payment ID</th>
                <th>User ID</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Payment Proof</th>
                <th>Action</th>
            </tr>
            <?php foreach ($pendingPayments as $payment): ?>
                <tr>
                    <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                    <td><?= htmlspecialchars($payment['user_id']) ?></td>
                    <td><?= htmlspecialchars($payment['payment_amount']) ?></td>
                    <td><?= htmlspecialchars($payment['payment_date']) ?></td>
                    <td><a href="<?= htmlspecialchars($payment['payment_proof_url']) ?>" target="_blank">View Proof</a></td>
                    <td class="status-actions">
                        <a href="verify_actions/verify_payment.php?id=<?= $payment['payment_id'] ?>">Verify</a>
                        <a href="verify_actions/reject_payment.php?id=<?= $payment['payment_id'] ?>">Reject</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Verified Payments -->
    <div class="payment-category verified">
        <h2>Verified Payments</h2>
        <table border="1">
            <tr>
                <th>Payment ID</th>
                <th>User ID</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Payment Proof</th>
                <th>Status</th>
            </tr>
            <?php foreach ($verifiedPayments as $payment): ?>
                <tr>
                    <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                    <td><?= htmlspecialchars($payment['user_id']) ?></td>
                    <td><?= htmlspecialchars($payment['payment_amount']) ?></td>
                    <td><?= htmlspecialchars($payment['payment_date']) ?></td>
                    <td><a href="<?= htmlspecialchars($payment['payment_proof_url']) ?>" target="_blank">View Proof</a></td>
                    <td>Verified</td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Failed Payments -->
    <div class="payment-category failed">
        <h2>Failed Payments</h2>
        <table border="1">
            <tr>
                <th>Payment ID</th>
                <th>User ID</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Payment Proof</th>
                <th>Status</th>
            </tr>
            <?php foreach ($failedPayments as $payment): ?>
                <tr>
                    <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                    <td><?= htmlspecialchars($payment['user_id']) ?></td>
                    <td><?= htmlspecialchars($payment['payment_amount']) ?></td>
                    <td><?= htmlspecialchars($payment['payment_date']) ?></td>
                    <td><a href="<?= htmlspecialchars($payment['payment_proof_url']) ?>" target="_blank">View Proof</a></td>
                    <td>Failed</td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>
