<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';


// Check if the user has admin access
checkAccess(1);  // 1 is the role_id for Admin

// Fetch all membership plans
$sql = "SELECT * FROM membership_plans";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Membership Plans</title>
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
            margin-bottom: 1rem;
        }

        /* Links */
        a {
            color: #a9a9b8;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin: 1rem;
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

        /* Table Styling */
        table {
            width: 100%;
            margin-top: 2rem;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: center;
            border: 1px solid #444;
        }

        th {
            background-color: #2e2e3a;
            box-shadow: inset 4px 4px 8px #141424, inset -4px -4px 8px #282844;
        }

        td {
            background-color: #1e1e2f;
            box-shadow: inset 4px 4px 8px #141424, inset -4px -4px 8px #282844;
        }

        td a {
            color: #a9a9b8;
            text-decoration: none;
            margin: 0 0.5rem;
            padding: 0.25rem;
            border-radius: 4px;
            background-color: #1e1e2f;
            box-shadow: 4px 4px 8px #141424, -4px -4px 8px #282844;
            transition: color 0.2s, box-shadow 0.2s;
        }

        td a:hover {
            color: #fff;
            box-shadow: 2px 2px 4px #141424, -2px -2px 4px #282844;
        }
    </style>
</head>

<body>
    <h1>Manage Membership Plans</h1>
    <a href="dashboard.php">Back to Dashboard</a>

    <a href="plans_actions/create_plan.php">Create New Plan</a>

    <table>
        <tr>
            <th>Plan ID</th>
            <th>Plan Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Duration (days)</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($plans as $plan): ?>
            <tr>
                <td><?= htmlspecialchars($plan['plan_id']) ?></td>
                <td><?= htmlspecialchars($plan['plan_name']) ?></td>
                <td><?= htmlspecialchars($plan['description']) ?></td>
                <td><?= htmlspecialchars($plan['price']) ?></td>
                <td><?= htmlspecialchars($plan['duration']) ?></td>
                <td>
                    <a href="plans_actions/edit_plan.php?id=<?= $plan['plan_id'] ?>">Edit</a>
                    <a href="plans_actions/delete_plan.php?id=<?= $plan['plan_id'] ?>"
                        onclick="return confirm('Are you sure you want to delete this plan?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>
