    <?php
    // Start the session to access data from the login page
    session_start();

    // Redirect to the login page if the 'username' is not set in the session.
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    // Get the username from the session and sanitize it for security
    $username = htmlspecialchars($_SESSION['username']);

    // Get the view from the URL parameter, default to 'analyze'
    $view = isset($_GET['view']) ? $_GET['view'] : 'analyze';
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>AI Retinopathy Dashboard</title>
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="sidebar.css">
        <style>
            .welcome-card {
                background-color: var(--card-background);
                padding: 40px;
                border-radius: 10px;
                box-shadow: var(--shadow-heavy);
                text-align: center;
                margin-bottom: 30px;
                max-width: 500px;
                width: 90%;
                height: auto;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .welcome-card h1 {
                color: var(--primary-color);
                margin-bottom: 10px;
            }

            .welcome-card p {
                color: var(--light-text-color);
                font-size: 1.1em;
            }
            .nav-buttons {
                display: flex;
                justify-content: center;
                gap: 40px;
                margin-top: 25px;
            }
            .action-btn {
                display: flex;
                align-items: center;
                padding: 12px 25px;
                border: 2px solid var(--primary-color);
                background-color: var(--card-background);
                color: var(--primary-color);
                text-decoration: none;
                border-radius: 5px;
                font-size: 1em;
                font-weight: bold;
                transition: all 0.3s ease;
            }

            .action-btn i {
                margin-right: 8px;
            }

            .action-btn:hover,
            .action-btn.active {
                background-color: var(--primary-color);
                color: white;
                transform: translateY(-3px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
        </style>
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
            <!-- Main Content Area -->
            <div class="main-content">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="welcome-card">
                    <h1>Welcome, <?php echo $username; ?>!</h1>
                    <p>We're here to help you analyze your retinal images and track your health history.</p>
                    <div class="nav-buttons">
                        <a href="index.html" class="action-btn <?php echo ($view == 'analyze') ? 'active' : ''; ?>">
                            <i class="fas fa-microscope"></i> Analyze
                        </a>
                        <a href="dashboard.php?view=history" class="action-btn <?php echo ($view == 'history') ? 'active' : ''; ?>">
                            <i class="fas fa-history"></i> View History
                        </a>
                    </div>
                </div>

                <?php
                // The content for the different sections is defined here
                $history_content = '
                    <div class="content-section">
                        <h2>Patient History</h2>
                        <div id="history-list">
                            <p>No history found.</p>
                        </div>
                    </div>';

                // Use PHP to echo the correct content based on the view
                if ($view == 'history') {
                    echo $history_content;
                }
                ?>
            </div>      
        </div>
        <script>
            function toggleSidebar() {
                const sidebar = document.querySelector('.sidebar');
                sidebar.classList.toggle('hidden');
            }
        </script>
    </body>
    </html>

