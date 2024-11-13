 
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('../config/database.php');
    
    $payment_id = $_POST['payment_id'];
    
    // Update payment status to 'verified'
    $stmt = $pdo->prepare("UPDATE payments SET status = 'verified' WHERE payment_id = ?");
    $stmt->execute([$payment_id]);
    
    echo "Payment verified successfully.";
}
?>
