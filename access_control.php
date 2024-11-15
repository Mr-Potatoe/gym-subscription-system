<?php
// Start the session only if one is not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkAccess($requiredRole) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != $requiredRole) {
        echo "Access denied. You do not have permission to view this page.";
        exit;
    }
}
?>
