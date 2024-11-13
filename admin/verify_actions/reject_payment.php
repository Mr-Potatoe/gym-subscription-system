<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';

// Check if the user has admin access
checkAccess(1);  // 1 is the role_id for Admin

// Get the payment ID from the URL
if (!isset($_GET['id'])) {
    echo "Payment ID is missing.";
    exit;
}

$paymentId = $_GET['id'];

// Update the payment status to 'failed'
$sql = "UPDATE payments SET payment_status = 'failed' WHERE payment_id = :payment_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['payment_id' => $paymentId]);

// Redirect to the payment verification page with a failure message
header('Location: ../verify_payments.php?status=rejected');
exit;
?>
