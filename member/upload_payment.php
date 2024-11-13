<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');  // Redirect if not logged in
    exit;
}

$userId = $_SESSION['user_id'];

// Check if membership is pending
$sql = "SELECT * FROM memberships WHERE user_id = :user_id AND status = 'pending'";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $userId]);
$membership = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$membership) {
    echo "No pending membership application found.";
    exit;
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['payment_proof']) && isset($_POST['payment_method'])) {
    $paymentProof = $_FILES['payment_proof'];
    $paymentMethod = $_POST['payment_method'];  // Get the selected payment method
    
    // Define the target directory relative to the web server document root
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/uploads/';
    $targetFile = $targetDir . basename($paymentProof['name']);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is a valid image or document
    if ($paymentProof['size'] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif" && $fileType != "pdf") {
        echo "Sorry, only JPG, JPEG, PNG, GIF, and PDF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Ensure the uploads directory exists
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Create directory if it doesn't exist
        }

        if (move_uploaded_file($paymentProof['tmp_name'], $targetFile)) {
            // Debugging: Output the file path
            echo "File uploaded to: " . $targetFile;

            // Check if a payment record exists for the user
            $sql = "SELECT * FROM payments WHERE user_id = :user_id AND payment_status = 'pending'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            $paymentRecord = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($paymentRecord) {
                // Update the payment record with the proof URL and payment method
                $sql = "UPDATE payments SET payment_proof_url = :payment_proof_url, payment_method = :payment_method WHERE user_id = :user_id AND payment_status = 'pending'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'payment_proof_url' => '/gym-subscription-system/uploads/' . basename($paymentProof['name']),
                    'payment_method' => $paymentMethod,
                    'user_id' => $userId
                ]);

                // After successful upload and update, redirect to avoid multiple submissions
                header('Location: upload_payment.php?status=success');
                exit;  // Ensure no further code is executed
            } else {
                echo "No pending payment record found for the user.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Payment Proof</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Upload Payment Proof</h1>

    <!-- Show success message if the upload was successful -->
    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <p style="color: green;">Payment proof uploaded successfully!</p>
    <?php endif; ?>

    <form action="upload_payment.php" method="POST" enctype="multipart/form-data">
        <label for="payment_proof">Choose a payment proof file:</label>
        <input type="file" name="payment_proof" id="payment_proof" required>
        
        <br><br>

        <label for="payment_method">Select Payment Method:</label>
        <select name="payment_method" id="payment_method" required>
            <option value="Credit Card">Credit Card</option>
            <option value="PayPal">PayPal</option>
            <option value="Bank Transfer">Bank Transfer</option>
            <option value="Cash">Cash</option>
        </select>

        <br><br>
        <button type="submit">Upload Payment Proof</button>
    </form>

    <nav>
        <a href="../admin/dashboard.php">Back to Profile</a>
    </nav>
</body>
</html>
