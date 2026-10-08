<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('TUTOR');
require_once __DIR__ . '/../includes/header.php';

$stmt = $pdo->prepare('SELECT r.*, u.name AS reviewer_name FROM reviews r JOIN users u ON u.id = r.reviewer_id WHERE r.reviewed_user_id = ? ORDER BY r.created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$reviews = $stmt->fetchAll();
?>

<div class="container" style="padding:2rem 0;">
    <h2>পর্যালোচনা</h2>
    <?php if (empty($reviews)): ?>
        <div class="card"><p>এখনও কোনো পর্যালোচনা নেই।</p></div>
    <?php else: ?>
        <div style="display:grid; gap:1rem;">
            <?php foreach ($reviews as $review): ?>
                <div class="card">
                    <h3><?php echo e($review['reviewer_name']); ?></h3>
                    <p>রেটিং: <?php echo (int)$review['rating']; ?>/৫</p>
                    <p><?php echo e($review['comment']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
