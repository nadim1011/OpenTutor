<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('ADMIN');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $userId = (int)($_POST['user_id'] ?? 0);
    $status = $_POST['status'] ?? 'REJECTED';
    $pdo->prepare('UPDATE tutor_profiles SET verification_status = ? WHERE user_id = ?')->execute([$status, $userId]);
    $_SESSION['success'] = 'শিক্ষক যাচাই আপডেট হয়েছে।';
    redirect('verify-tutors.php');
}
require_once __DIR__ . '/../includes/header.php';
$rows = $pdo->query('SELECT u.id, u.name, tp.verification_status FROM users u JOIN tutor_profiles tp ON tp.user_id = u.id WHERE u.role = "TUTOR" ORDER BY u.created_at DESC')->fetchAll();
?>

<div class="container" style="padding:2rem 0;">
    <h2>শিক্ষক যাচাই</h2>
    <?php if (empty($rows)): ?>
        <div class="card"><p>কোনো যাচাইয়ের আবেদন নেই।</p></div>
    <?php else: ?>
        <?php foreach ($rows as $row): ?>
            <div class="card" style="margin-bottom: 1rem;">
                <h3><?php echo e($row['name']); ?></h3>
                <p>স্ট্যাটাস: <?php echo e($row['verification_status']); ?></p>
                <form action="verify-tutors.php" method="POST">
                    <?php echo csrf_input(); ?>
                    <input type="hidden" name="user_id" value="<?php echo (int)$row['id']; ?>">
                    <button type="submit" name="status" value="VERIFIED" class="btn btn-primary">✓ যাচাই করুন</button>
                    <button type="submit" name="status" value="REJECTED" class="btn btn-outline">✕ প্রত্যাখ্যান করুন</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
