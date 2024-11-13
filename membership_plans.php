<?php
include 'db.php';

function getAllMembershipPlans() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM membership_plans");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createMembershipPlan($planName, $description, $price, $duration) {
    global $pdo;
    $sql = "INSERT INTO membership_plans (plan_name, description, price, duration) 
            VALUES (:plan_name, :description, :price, :duration)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['plan_name' => $planName, 'description' => $description, 'price' => $price, 'duration' => $duration]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $planName = $_POST['plan_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    
    createMembershipPlan($planName, $description, $price, $duration);
    echo "Membership plan created successfully!";
}
?>

<form method="POST">
    <input type="text" name="plan_name" placeholder="Plan Name" required><br>
    <textarea name="description" placeholder="Description" required></textarea><br>
    <input type="number" name="price" placeholder="Price" required><br>
    <input type="number" name="duration" placeholder="Duration (days)" required><br>
    <button type="submit">Create Plan</button>
</form>
