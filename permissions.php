<?php
include 'db.php';

function getAllPermissions() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM permissions");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function assignPermissionToStaff($staffId, $permissionId) {
    global $pdo;
    $sql = "INSERT INTO staff_permissions (staff_id, permission_id) VALUES (:staff_id, :permission_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['staff_id' => $staffId, 'permission_id' => $permissionId]);
}

$permissions = getAllPermissions();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $staffId = $_POST['staff_id'];
    $permissionId = $_POST['permission_id'];
    assignPermissionToStaff($staffId, $permissionId);
    echo "Permission assigned successfully!";
}
?>

<form method="POST">
    <select name="staff_id">
        <!-- Populate with staff data -->
    </select><br>
    <select name="permission_id">
        <?php
        foreach ($permissions as $permission) {
            echo "<option value='{$permission['permission_id']}'>{$permission['permission_name']}</option>";
        }
        ?>
    </select><br>
    <button type="submit">Assign Permission</button>
</form>
