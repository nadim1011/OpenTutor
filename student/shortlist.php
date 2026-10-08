<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/layout-header.php';

$stmt = $pdo->prepare('SELECT s.*, u.name FROM shortlists s JOIN users u ON u.id = s.target_id WHERE s.user_id = ? AND s.target_type = ? ORDER BY s.created_at DESC');
$stmt->execute([$_SESSION['user_id'], 'TUTOR']);
$shortlists = $stmt->fetchAll();
?>

<h2>পছন্দের শিক্ষক</h2>
<?php if (empty($shortlists)): ?>
    <div class="card"><p>এখনও কোনো পছন্দের শিক্ষক নেই।</p></div>
<?php else: ?>
    <div style="display: grid; gap: 1rem;">
        <?php foreach ($shortlists as $item): ?>
            <div class="card">
                <h3><?php echo e($item['name']); ?></h3>
                <a href="../teacher-details.php?id=<?php echo (int)$item['target_id']; ?>" class="btn btn-outline">প্রোফাইল দেখুন</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/layout-footer.php'; ?>
