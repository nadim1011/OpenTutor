<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('TUTOR');
require_once __DIR__ . '/../includes/header.php';

$stmt = $pdo->prepare('SELECT s.*, tp.title FROM shortlists s JOIN tuition_posts tp ON tp.id = s.target_id WHERE s.user_id = ? AND s.target_type = ? ORDER BY s.created_at DESC');
$stmt->execute([$_SESSION['user_id'], 'TUITION']);
$items = $stmt->fetchAll();
?>

<div class="container" style="padding:2rem 0;">
    <h2>পছন্দের টিউশন</h2>
    <?php if (empty($items)): ?>
        <div class="card"><p>কোনো পছন্দের টিউশন নেই।</p></div>
    <?php else: ?>
        <div style="display:grid; gap:1rem;">
            <?php foreach ($items as $item): ?>
                <div class="card">
                    <h3><?php echo e($item['title']); ?></h3>
                    <a href="../tuition-details.php?id=<?php echo (int)$item['target_id']; ?>" class="btn btn-outline">বিস্তারিত</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
