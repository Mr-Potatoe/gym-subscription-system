<?php
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/gym-subscription-system/access_control.php';

// Check if the user has admin access
checkAccess(1);  // 1 is the role_id for Admin

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Dark Background */
        body {
            background-color: #1e1e2f;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
        }

        /* Header Styling */
        h1 {
            font-size: 2.5rem;
            margin: 1rem 0;
            color: #fff;
            text-align: center;
        }

        p {
            font-size: 1rem;
            color: #a9a9b8;
            text-align: center;
            margin-bottom: 2rem;
        }

        /* Navigation */
        nav {
            background-color: #1e1e2f;
            padding: 1.5rem;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 80%;
            max-width: 600px;
            box-shadow: 9px 9px 16px #141424, -9px -9px 16px #282844;
        }

        nav a {
            color: #a9a9b8;
            text-decoration: none;
            font-weight: bold;
            padding: 0.75rem 2rem;
            margin: 0.5rem 0;
            border-radius: 8px;
            background-color: #1e1e2f;
            box-shadow: 6px 6px 12px #141424, -6px -6px 12px #282844;
            transition: color 0.3s ease, box-shadow 0.2s ease-in-out;
            width: 100%;
            text-align: center;
        }

        nav a:hover {
            color: #fff;
            box-shadow: 4px 4px 8px #141424, -4px -4px 8px #282844;
        }

        nav a:active {
            box-shadow: inset 6px 6px 12px #141424, inset -6px -6px 12px #282844;
        }
    </style>
</head>
<body>

<h1>Admin Dashboard</h1>
<p>Welcome to the admin section. Here you can manage users, membership plans, and payments.</p>

<nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_plans.php">Manage Membership Plans</a>
    <a href="verify_payments.php">Verify Payments</a>
    <a href="../logout.php">Logout</a>
</nav>

</body>
</html>

