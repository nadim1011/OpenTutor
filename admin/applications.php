<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('ADMIN');
require_once __DIR__ . '/../includes/header.php';

$applications = $pdo->query('SELECT a.*, tp.title, u.name AS tutor_name FROM applications a JOIN tuition_posts tp ON tp.id = a.tuition_id JOIN users u ON u.id = a.tutor_id ORDER BY a.created_at DESC')->fetchAll();
?>

<div class="container" style="padding:2rem 0;">
    <h2>আবেদন</h2>
    <div style="display:grid; gap:1rem;">
        <?php foreach ($applications as $application): ?>
            <div class="card">
                <h3><?php echo e($application['title']); ?></h3>
                <p>শিক্ষক: <?php echo e($application['tutor_name']); ?> • অবস্থান: <?php echo e($application['status']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
