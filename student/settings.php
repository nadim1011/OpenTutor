<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('STUDENT');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    if ($name === '' || $phone === '') {
        $_SESSION['error'] = 'নাম ও ফোন নম্বর আবশ্যক।';
    } else {
        $stmt = $pdo->prepare('UPDATE users SET name = ?, phone = ? WHERE id = ?');
        $stmt->execute([$name, $phone, $_SESSION['user_id']]);
        $_SESSION['success'] = 'আপনার সেটিংস আপডেট করা হয়েছে।';
        redirect('settings.php');
    }
}

require_once __DIR__ . '/layout-header.php';
$user = currentUser($pdo);
?>

<h2>সেটিংস</h2>
<form action="settings.php" method="POST">
    <?php echo csrf_input(); ?>
    <div class="form-group">
        <label class="form-label">নাম</label>
        <input type="text" name="name" class="form-control" value="<?php echo e($user['name']); ?>" required>
    </div>
    <div class="form-group">
        <label class="form-label">ফোন নম্বর</label>
        <input type="text" name="phone" class="form-control" value="<?php echo e($user['phone']); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">আপডেট করুন</button>
</form>

<?php require_once __DIR__ . '/layout-footer.php'; ?>
