<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('TUTOR');
require_once __DIR__ . '/../includes/header.php';

$stmt = $pdo->prepare('SELECT a.*, tp.title FROM applications a JOIN tuition_posts tp ON tp.id = a.tuition_id WHERE a.tutor_id = ? ORDER BY a.created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$applications = $stmt->fetchAll();
?>

<div class="container" style="padding:2rem 0;">
    <h2>আমার আবেদন</h2>
    <?php if (empty($applications)): ?>
        <div class="card"><p>কোনো আবেদন করা হয়নি।</p></div>
    <?php else: ?>
        <div style="display:grid; gap:1rem;">
            <?php foreach ($applications as $application): ?>
                <div class="card">
                    <h3><?php echo e($application['title']); ?></h3>
                    <p>অবস্থা: <span class="badge"><?php echo pretty_status($application['status']); ?></span></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
