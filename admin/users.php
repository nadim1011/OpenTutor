<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('ADMIN');
require_once __DIR__ . '/../includes/header.php';

$users = $pdo->query('SELECT * FROM users ORDER BY created_at DESC')->fetchAll();
?>

<div class="container" style="padding:2rem 0;">
    <h2>ব্যবহারকারী</h2>
    <div style="display:grid; gap:1rem; margin-top:1rem;">
        <?php foreach ($users as $user): ?>
            <div class="card">
                <h3><?php echo e($user['name']); ?></h3>
                <p><?php echo e($user['email']); ?> • <?php echo get_role_bn($user['role']); ?></p>
                <p>অবস্থা: <?php echo pretty_status($user['status']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
