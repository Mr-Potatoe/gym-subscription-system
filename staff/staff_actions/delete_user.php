<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';

// Check if the user has Staff access
checkAccess(2);  // 2 is the role_id for Staff

// Get the user ID from the URL
$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// First, delete any memberships associated with the user
$sql = "DELETE FROM memberships WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $userId]);

// Then, delete the user from the database
$sql = "DELETE FROM users WHERE user_id = :user_id AND role_id = 3";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $userId]);

// Redirect back to Manage Members page with a success message
header("Location: ../member_management.php?msg=User deleted successfully");
exit;
?>
