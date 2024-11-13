<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';

// Check if the user has admin access
checkAccess(1);  // 1 is the role_id for Admin

// Check if an ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];

    // Begin a transaction to ensure atomicity
    $pdo->beginTransaction();

    try {
        // First, delete associated records in the payments table
        $deletePaymentsSql = "DELETE FROM payments WHERE user_id = :user_id";
        $stmt = $pdo->prepare($deletePaymentsSql);
        $stmt->execute(['user_id' => $userId]);

        // Then, delete the user from the users table
        $deleteUserSql = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $pdo->prepare($deleteUserSql);
        $stmt->execute(['user_id' => $userId]);

        // Commit the transaction
        $pdo->commit();

        header("Location: ../manage_users.php?msg=User+deleted+successfully");
        exit;
    } catch (Exception $e) {
        // Roll back the transaction if anything fails
        $pdo->rollBack();
        header("Location: ../manage_users.php?msg=Error+deleting+user");
        exit;
    }
} else {
    // If no valid ID is provided, redirect back with an error message
    header("Location: ../manage_users.php?msg=Invalid+user+ID");
    exit;
}
?>
