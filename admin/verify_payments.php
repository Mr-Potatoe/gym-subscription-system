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
            margin-bottom: 2rem;
        }

        a {
            color: #a9a9b8;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 1.5rem;
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

        /* Payment Categories Styling */
        .payment-category {
            margin-bottom: 20px;
            padding: 15px;
            width: 90%;
            max-width: 900px;
            background-color: #2e2e3a;
            border-radius: 12px;
            box-shadow: 6px 6px 12px #141424, -6px -6px 12px #282844;
        }

        .payment-category h2 {
            margin-top: 0;
            font-size: 1.4rem;
        }

        .pending { background-color: #1e1e2f; }
        .verified { background-color: #1e1e2f; }
        .failed { background-color: #1e1e2f; }

        /* Table Styling */
        table {
            width: 100%;
            margin-top: 1rem;
            border-collapse: collapse;
        }

        th, td {
            padding: 0.8rem;
            text-align: left;
            border: 1px solid #444;
        }

        th {
            background-color: #333;
        }

        td a {
            color: #1e90ff;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        .status-actions {
            display: flex;
            gap: 10px;
        }

        .status-actions a {
            color: #fff;
            padding: 0.5rem 1rem;
            background-color: #444;
            border-radius: 8px;
            box-shadow: 4px 4px 8px #141424, -4px -4px 8px #282844;
            transition: background-color 0.2s;
        }

        .status-actions a:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <h1>Verify Payments</h1>
    <a href="dashboard.php">Back to Dashboard</a>

    <!-- Pending Payments -->
    <div class="payment-category pending">
        <h2>Pending Payments</h2>
        <table>
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
        <table>
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
        <table>
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

