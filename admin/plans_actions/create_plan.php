<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';

// Check if the user has admin access
checkAccess(1);  // 1 is the role_id for Admin

// Handle form submission to create a new plan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $planName = $_POST['plan_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];

    // Validation
    if (empty($planName) || empty($description) || empty($price) || empty($duration)) {
        $error = "All fields are required.";
    } else {
        // Insert new plan into the database
        $sql = "INSERT INTO membership_plans (plan_name, description, price, duration) 
                VALUES (:plan_name, :description, :price, :duration)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'plan_name' => $planName,
            'description' => $description,
            'price' => $price,
            'duration' => $duration
        ]);
        
        $success = "Plan created successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Membership Plan</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Body Styling */
        body {
            background-color: #1e1e2f;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
        }

        h1 {
            color: #fff;
            text-align: center;
            margin-bottom: 2rem;
        }

        /* Links */
        a {
            color: #a9a9b8;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 1.5rem;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            background-color: #1e1e2f;
            box-shadow: 6px 6px 12px #141424, -6px -6px 12px #282844;
            transition: color 0.2s, box-shadow 0.2s;
        }

        a:hover {
            color: #fff;
            box-shadow: 4px 4px 8px #141424, -4px -4px 8px #282844;
        }

        /* Form Styling */
        form {
            background-color: #2e2e3a;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 6px 6px 12px #141424, -6px -6px 12px #282844;
            width: 100%;
            max-width: 600px;
            margin-top: 2rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        input, textarea {
            width: 100%;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #1e1e2f;
            color: #fff;
            border: 1px solid #444;
            border-radius: 8px;
            box-shadow: inset 4px 4px 8px #141424, inset -4px -4px 8px #282844;
            font-size: 1rem;
        }

        button {
            padding: 1rem 2rem;
            background-color: #444;
            color: #fff;
            font-size: 1.1rem;
            border: none;
            border-radius: 8px;
            box-shadow: 4px 4px 8px #141424, -4px -4px 8px #282844;
            cursor: pointer;
            transition: background-color 0.2s, box-shadow 0.2s;
        }

        button:hover {
            background-color: #333;
            box-shadow: 4px 4px 8px #141424, -4px -4px 8px #282844;
        }

        /* Error and Success Messages */
        p {
            text-align: center;
            font-size: 1rem;
        }

        p[style*="color: red"] {
            color: #ff6f6f;
        }

        p[style*="color: green"] {
            color: #76ff7b;
        }
    </style>
</head>
<body>
    <h1>Create New Membership Plan</h1>

    <form action="create_plan.php" method="POST">
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p style="color: green;"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <label for="plan_name">Plan Name:</label>
        <input type="text" name="plan_name" id="plan_name" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" required>

        <label for="duration">Duration (days):</label>
        <input type="number" name="duration" id="duration" required>

        <button type="submit">Create Plan</button>
    </form>

    <a href="../dashboard.php">Back to Dashboard</a>
</body>
</html>

