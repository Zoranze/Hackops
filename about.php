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

// Set view for active menu highlight
$view = 'about';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - AI Retinopathy Dashboard</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="sidebar.css">
</head>
<body>
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

    <div class="main-content">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="content-card">
            <h1>About This Project</h1>
            <p>Welcome, <?php echo $username; ?>! This AI Retinopathy Dashboard helps users analyze retinal images to detect potential signs of diabetic retinopathy.</p>

            <h2><i class="fas fa-bullseye"></i> Our Goal</h2>
            <p>Our mission is to provide an accessible, easy-to-use tool for early detection of eye diseases, empowering patients and healthcare professionals.</p>

            <h2><i class="fas fa-cogs"></i> Technologies Used</h2>
            <ul>
                <li><strong>Frontend:</strong> HTML, CSS, JavaScript</li>
                <li><strong>Backend:</strong> PHP</li>
                <li><strong>AI Model:</strong> Trained deep learning model for image analysis</li>
                <li><strong>Database:</strong> MySQL</li>
            </ul>

            <h2><i class="fas fa-users"></i> Team</h2>
            <p>This project was built by a passionate team of developers and data scientists to improve accessibility to healthcare diagnostics.</p>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            sidebar.classList.toggle('hidden');
            mainContent.classList.toggle('full-width');
        }
    </script>
</body>
</html>
