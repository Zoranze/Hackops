<?php
// Start the session to access user data
session_start();

// Redirect to the login page if the 'username' is not set
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the username from the session and sanitize it for security
$username = htmlspecialchars($_SESSION['username']);
$view = 'help'; // Set the active view for the help page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help - AI Retinopathy Dashboard</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="sidebar.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-info">
            <i class="fas fa-user-circle"></i>
        </div>
        <nav>
            <ul>
                <li><a href="dashboard.php" class="<?php echo ($view == 'analyze') ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="help.php" class="<?php echo ($view == 'help') ? 'active' : ''; ?>"><i class="fas fa-question-circle"></i> Help</a></li>
                <li><a href="about.php" class="<?php echo ($view == 'about') ? 'active' : ''; ?>"><i class="fas fa-info-circle"></i> About</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="help-content">
            <h1>Website User Guide</h1>
            <p>Welcome, <?php echo $username; ?>! This guide will walk you through the key features of the application.</p>
            
            <h2><i class="fas fa-desktop"></i> Dashboard</h2>
            <p>After logging in, you are taken to the <strong>Dashboard</strong>. This is your central hub for accessing all the application's features.</p>

            <h2><i class="fas fa-microscope"></i> Image Analysis</h2>
            <p>To perform a new analysis, click the <strong>Analyze</strong> button. You will be redirected to the analysis page, where you can upload a retinal image. Our AI system will then provide a diagnosis and confidence score.</p>

            <h2><i class="fas fa-history"></i> View History</h2>
            <p>The <strong>View History</strong> button takes you to a page where you can see all your previous analyses. This feature helps you track your health data over time.</p>

            <h2><i class="fas fa-question-circle"></i> Help & Support</h2>
            <p>You are currently on this page! It provides a comprehensive guide on how to use the website. If you have any further questions, please reach out to our support team.</p>
            
            <h2><i class="fas fa-info-circle"></i> About This Project</h2>
            <p>The <strong>About</strong> page gives you information about the purpose of this application, its technical details, and the team behind it. This tool was developed as a hackathon project to demonstrate the potential of AI in healthcare.</p>
            
            <h2><i class="fas fa-sign-out-alt"></i> Logout</h2>
            <p>To securely end your session, click the <strong>Logout</strong> button. You will be redirected to the login page.</p>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('hidden');
        }
    </script>
</body>
</html>
