<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/layout-header.php';

$stmt = $pdo->prepare('SELECT * FROM tuition_posts WHERE student_id = ? ORDER BY created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$tuitions = $stmt->fetchAll();
?>

<h2>আমার টিউশন</h2>
<?php if (empty($tuitions)): ?>
    <div class="card"><p>এখনও কোনো টিউশন পোস্ট নেই।</p></div>
<?php else: ?>
    <div style="display: grid; gap: 1rem;">
        <?php foreach ($tuitions as $tuition): ?>
            <div class="card">
                <div style="display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap; align-items: center;">
                    <div>
                        <h3><?php echo e($tuition['title']); ?></h3>
                        <p><?php echo e($tuition['subjects']); ?> • <?php echo e($tuition['location']); ?></p>
                    </div>
                    <span class="badge"><?php echo pretty_status($tuition['status']); ?></span>
                </div>
                <p><?php echo e($tuition['description']); ?></p>
                <a href="../tuition-details.php?id=<?php echo (int)$tuition['id']; ?>" class="btn btn-outline">বিস্তারিত</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/layout-footer.php'; ?>
