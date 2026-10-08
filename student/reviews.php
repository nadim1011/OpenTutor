<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/layout-header.php';

$stmt = $pdo->prepare('SELECT r.*, u.name AS reviewed_name FROM reviews r JOIN users u ON u.id = r.reviewed_user_id WHERE r.reviewer_id = ? ORDER BY r.created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$reviews = $stmt->fetchAll();
?>

<h2>পর্যালোচনা</h2>
<?php if (empty($reviews)): ?>
    <div class="card"><p>এখনও কোনো পর্যালোচনা নেই।</p></div>
<?php else: ?>
    <div style="display: grid; gap: 1rem;">
        <?php foreach ($reviews as $review): ?>
            <div class="card">
                <h3><?php echo e($review['reviewed_name']); ?></h3>
                <p>রেটিং: <?php echo (int)$review['rating']; ?>/৫</p>
                <p><?php echo e($review['comment']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/layout-footer.php'; ?>
