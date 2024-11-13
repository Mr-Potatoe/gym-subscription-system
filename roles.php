<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';


function createRole($roleName, $description) {
    global $pdo;
    $sql = "INSERT INTO roles (role_name, description) VALUES (:role_name, :description)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['role_name' => $roleName, 'description' => $description]);
}

function getAllRoles() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM roles");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
