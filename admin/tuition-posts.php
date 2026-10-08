<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('ADMIN');
require_once __DIR__ . '/../includes/header.php';

$posts = $pdo->query('SELECT tp.*, u.name AS student_name FROM tuition_posts tp JOIN users u ON u.id = tp.student_id ORDER BY tp.created_at DESC')->fetchAll();
?>

<div class="container" style="padding:2rem 0;">
    <h2>টিউশন পোস্ট</h2>
    <?php foreach ($posts as $post): ?>
        <div class="card" style="margin-bottom: 1rem;">
            <h3><?php echo e($post['title']); ?></h3>
            <p><?php echo e($post['student_name']); ?> • <?php echo e($post['location']); ?> • <?php echo e($post['status']); ?></p>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
