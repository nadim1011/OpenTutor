<?php
// opentutor/includes/navbar.php
?>
<nav class="navbar">
    <div class="container">
        <a href="<?php echo BASE_URL; ?>" class="logo"><?php echo APP_NAME_BN; ?></a>
        
        <button class="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="nav-links">
            <li><a href="<?php echo BASE_URL; ?>" class="nav-link">হোম</a></li>
            <li><a href="<?php echo BASE_URL; ?>teachers.php" class="nav-link">শিক্ষক খুঁজুন</a></li>
            <li><a href="<?php echo BASE_URL; ?>tuitions.php" class="nav-link">টিউশন খুঁজুন</a></li>
            <li><a href="<?php echo BASE_URL; ?>how-it-works.php" class="nav-link">কীভাবে কাজ করে</a></li>
            <li><a href="<?php echo BASE_URL; ?>about.php" class="nav-link">আমাদের সম্পর্কে</a></li>
            
            <?php if (isLoggedIn()): ?>
                <?php 
                    $dashboard_url = 'student/index.php';
                    if ($_SESSION['user_role'] === 'ADMIN') $dashboard_url = 'admin/index.php';
                    elseif ($_SESSION['user_role'] === 'TUTOR') $dashboard_url = 'tutor/index.php';
                ?>
                <li><a href="<?php echo BASE_URL . $dashboard_url; ?>" class="btn btn-outline">ড্যাশবোর্ড</a></li>
                <li><a href="<?php echo BASE_URL; ?>logout.php" class="btn btn-primary">লগআউট</a></li>
            <?php else: ?>
                <li><a href="<?php echo BASE_URL; ?>login.php" class="btn btn-outline">লগইন</a></li>
                <li><a href="<?php echo BASE_URL; ?>register.php" class="btn btn-primary">নিবন্ধন</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
