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

// Fetch the payment details to check for proof and payment method
$sql = "SELECT payment_proof_url, payment_method, payment_status FROM payments WHERE payment_id = :payment_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['payment_id' => $paymentId]);
$payment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$payment) {
    echo "No payment found with that ID.";
    exit;
}

// Check if payment proof and payment method are provided
if (empty($payment['payment_proof_url']) || empty($payment['payment_method'])) {
    echo "Payment cannot be verified without payment proof or transaction method.";
    exit;  // Stop execution here if validation fails
}

// Check if the payment is already verified
if ($payment['payment_status'] === 'verified') {
    echo "This payment has already been verified.";
    exit;  // Stop execution if the payment has already been verified
}

// Update the payment status to 'verified'
$sql = "UPDATE payments SET payment_status = 'verified' WHERE payment_id = :payment_id";
$stmt = $pdo->prepare($sql);
if (!$stmt->execute(['payment_id' => $paymentId])) {
    echo "Failed to verify payment. Please try again.";
    exit;
}

// Fetch the user ID associated with this payment
$sql = "SELECT user_id FROM payments WHERE payment_id = :payment_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['payment_id' => $paymentId]);
$payment = $stmt->fetch(PDO::FETCH_ASSOC);

if ($payment) {
    // Now get the plan_id from the memberships table based on the user_id
    $sql = "SELECT plan_id FROM memberships WHERE user_id = :user_id AND status = 'pending'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $payment['user_id']]);
    $membership = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($membership) {
        // Set the start date to the current date when the admin verifies payment
        $sql = "UPDATE memberships 
                SET status = 'approved', membership_start_date = CURRENT_TIMESTAMP 
                WHERE user_id = :user_id AND plan_id = :plan_id";
        $stmt = $pdo->prepare($sql);
        if (!$stmt->execute([
            'user_id' => $payment['user_id'],
            'plan_id' => $membership['plan_id']
        ])) {
            echo "Failed to approve membership. Please try again.";
            exit;
        }
    }
}

// Redirect to the payment verification page with a success message
header('Location: ../verify_payments.php?status=verified');
exit;
?>
