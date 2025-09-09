<div class="sidebar">
    <div class="user-info">
        <i class="fas fa-user-circle"></i>
    </div>
    <nav>
        <ul>
            <li><a href="dashboard.php" class="<?php echo ($view == 'home') ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="help.php" class="<?php echo ($view == 'help') ? 'active' : ''; ?>"><i class="fas fa-question-circle"></i> Help</a></li>
            <li><a href="about.php" class="<?php echo ($view == 'about') ? 'active' : ''; ?>"><i class="fas fa-info-circle"></i> About</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
</div>