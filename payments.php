<?php
include 'db.php';

function getAllPayments() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM payments");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function recordPayment($userId, $amount, $method, $proofUrl) {
    global $pdo;
    $sql = "INSERT INTO payments (user_id, payment_amount, payment_method, payment_proof_url, payment_status) 
            VALUES (:user_id, :amount, :method, :proof_url, 'pending')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $userId, 'amount' => $amount, 'method' => $method, 'proof_url' => $proofUrl]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];
    $amount = $_POST['amount'];
    $method = $_POST['method'];
    $proofUrl = $_POST['proof_url'];
    
    recordPayment($userId, $amount, $method, $proofUrl);
    echo "Payment recorded successfully!";
}
?>

<form method="POST">
    <input type="number" name="user_id" placeholder="User ID" required><br>
    <input type="number" name="amount" placeholder="Amount" required><br>
    <input type="text" name="method" placeholder="Payment Method" required><br>
    <input type="text" name="proof_url" placeholder="Payment Proof URL" required><br>
    <button type="submit">Record Payment</button>
</form>
