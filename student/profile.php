<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('STUDENT');

$user = currentUser($pdo);
$stmt = $pdo->prepare('SELECT * FROM student_profiles WHERE user_id = ? LIMIT 1');
$stmt->execute([$_SESSION['user_id']]);
$profile = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guardian = trim($_POST['guardian_name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    if ($profile) {
        $pdo->prepare('UPDATE student_profiles SET guardian_name = ?, location = ?, bio = ? WHERE user_id = ?')->execute([$guardian, $location, $bio, $_SESSION['user_id']]);
    } else {
        $pdo->prepare('INSERT INTO student_profiles (user_id, guardian_name, location, bio) VALUES (?, ?, ?, ?)')->execute([$_SESSION['user_id'], $guardian, $location, $bio]);
    }
    $_SESSION['success'] = 'আপনার প্রোফাইল আপডেট হয়েছে।';
    redirect('profile.php');
}
require_once __DIR__ . '/layout-header.php';
?>

<h2>আমার প্রোফাইল</h2>
<form action="profile.php" method="POST" class="card" style="margin-top: 1rem;">
    <?php echo csrf_input(); ?>
    <div class="form-group"><label class="form-label">অভিভাবকের নাম</label><input type="text" name="guardian_name" class="form-control" value="<?php echo e($profile['guardian_name'] ?? ''); ?>"></div>
    <div class="form-group"><label class="form-label">ঠিকানা</label><input type="text" name="location" class="form-control" value="<?php echo e($profile['location'] ?? ''); ?>"></div>
    <div class="form-group"><label class="form-label">নিজের সম্পর্কে</label><textarea name="bio" class="form-control" rows="5"><?php echo e($profile['bio'] ?? ''); ?></textarea></div>
    <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
</form>

<?php require_once __DIR__ . '/layout-footer.php'; ?>
