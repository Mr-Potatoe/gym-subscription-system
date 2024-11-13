<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';

// Check if the user has admin access
checkAccess(1);  // 1 is the role_id for Admin

// Check if an ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $planId = $_GET['id'];

    try {
        // Delete the plan from the membership_plans table
        $sql = "DELETE FROM membership_plans WHERE plan_id = :plan_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['plan_id' => $planId]);

        header("Location: ../manage_plans.php?msg=Plan+deleted+successfully");
        exit;
    } catch (Exception $e) {
        header("Location: ../manage_plans.php?msg=Error+deleting+plan");
        exit;
    }
} else {
    // If no valid ID is provided, redirect back with an error message
    header("Location: ../manage_plans.php?msg=Invalid+plan+ID");
    exit;
}
?>
