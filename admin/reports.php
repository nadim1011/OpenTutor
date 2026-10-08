<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('ADMIN');
require_once __DIR__ . '/../includes/header.php';

$reports = $pdo->query('SELECT r.*, u.name AS reporter_name FROM reports r JOIN users u ON u.id = r.reporter_id ORDER BY r.created_at DESC')->fetchAll();
?>

<div class="container" style="padding:2rem 0;">
    <h2>রিপোর্ট</h2>
    <?php foreach ($reports as $report): ?>
        <div class="card" style="margin-bottom: 1rem;">
            <h3><?php echo e($report['reason']); ?></h3>
            <p>রিপোর্টার: <?php echo e($report['reporter_name']); ?> • স্ট্যাটাস: <?php echo e($report['status']); ?></p>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
