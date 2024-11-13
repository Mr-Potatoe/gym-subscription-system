 
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Subscription Dashboard</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to the Gym Subscription Dashboard</h1>
        <div>
            <p>Subscription Status: Active</p>
            <p>Next Renewal: 2024-12-01</p>
        </div>
        <div>
            <h2>Upload Payment Screenshot</h2>
            <form action="upload_payment.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="payment_screenshot" accept="image/*" required>
                <button type="submit">Upload Payment</button>
            </form>
        </div>
    </div>
</body>
</html>
