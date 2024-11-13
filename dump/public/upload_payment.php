 
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['payment_screenshot'])) {
    require_once('../config/database.php');
    require_once('../includes/ocr_processing.php');
    
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["payment_screenshot"]["name"]);
    
    if (move_uploaded_file($_FILES["payment_screenshot"]["tmp_name"], $target_file)) {
        // Process the screenshot and get the payment amount
        $amount = process_payment_screenshot($target_file);
        
        // Insert the payment into the database (status set to 'pending')
        $stmt = $pdo->prepare("INSERT INTO payments (user_id, sub_id, payment_date, amount, screenshot_path) VALUES (?, ?, NOW(), ?, ?)");
        $stmt->execute([$_SESSION['user_id'], 1, $amount, $target_file]);
        
        echo "Payment screenshot uploaded successfully. Waiting for verification.";
    } else {
        echo "Error uploading the file.";
    }
}
?>
