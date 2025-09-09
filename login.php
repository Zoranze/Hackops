<?php
session_start();
require_once "db_connect.php"; 

$errorMsg = ""; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usernameInput = trim($_POST["username"]);
    $passwordInput = $_POST["password"];

    if (empty($usernameInput) || empty($passwordInput)) {
        $errorMsg = "Please fill in all fields.";
    } else {
        // Prepare statement to fetch user
        $sql = "SELECT userId, username, password FROM user WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $usernameInput);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $userData = $result->fetch_assoc();

                // Verify hashed password
                if (password_verify($passwordInput, $userData["password"])) {
                    // Store user data in session
                    $_SESSION["userId"] = $userData["userId"];
                    $_SESSION["username"] = $userData["username"];
                    session_regenerate_id(true); // optional: enhance session security

                    // Redirect to dashboard
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $errorMsg = "Invalid username or password.";
                }
            } else {
                $errorMsg = "Invalid username or password.";
            }
            $stmt->close();
        } else {
            $errorMsg = "Something went wrong. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f3f4f6;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        h2 { text-align: center; margin-bottom: 20px; }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            background: #2563eb;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover { background: #1e40af; }
        .error { color: red; text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($errorMsg)) echo "<p class='error'>$errorMsg</p>"; ?>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
