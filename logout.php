<?php
// Start the session
session_start();

// Check if the user has confirmed the logout.
if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
    // Destroy the session and all its data
    session_destroy();
    
    // Redirect to the login page
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .logout-card {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .logout-card h1 {
            color: #0f4c75;
            margin-bottom: 20px;
        }
        .logout-card p {
            font-size: 1.1em;
            color: #555;
            margin-bottom: 30px;
        }
        .logout-card .btn-group a {
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
            margin: 0 10px;
        }
        .logout-card .btn-group .confirm-btn {
            background-color: #d9534f; /* Red for emphasis */
            color: white;
        }
        .logout-card .btn-group .confirm-btn:hover {
            background-color:#a83f3cff;

        }
        .logout-card .btn-group .cancel-btn {
            background-color: #f0f0f0;
            color: #333;
        }
        .logout-card .btn-group .cancel-btn:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="logout-card">
        <h1>Confirm Logout</h1>
        <p>Are you sure you want to log out?</p>
        <div class="btn-group">
            <a href="logout.php?confirm=true" class="confirm-btn">Yes, Logout</a>
            <a href="javascript:history.back()" class="cancel-btn">Cancel</a>
        </div>
    </div>
</body>
</html>
