<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('TUTOR');
require_once __DIR__ . '/../includes/header.php';

$posts = $pdo->query("SELECT * FROM tuition_posts WHERE status = 'OPEN' ORDER BY created_at DESC")->fetchAll();
?>

<div class="container" style="padding:2rem 0;">
    <h2>টিউশন খুঁজুন</h2>
    <div style="display:grid; gap:1rem; margin-top:1.5rem;">
        <?php foreach ($posts as $post): ?>
            <div class="card">
                <h3><?php echo e($post['title']); ?></h3>
                <p><?php echo e($post['subjects']); ?> • <?php echo e($post['class_level']); ?> • <?php echo e($post['location']); ?></p>
                <p>বেতন: <?php echo format_bdt($post['salary'] ?? 0); ?></p>
                <a href="../tuition-details.php?id=<?php echo (int)$post['id']; ?>" class="btn btn-outline">বিস্তারিত</a>
                <a href="apply.php?id=<?php echo (int)$post['id']; ?>" class="btn btn-primary">আবেদন করুন</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
