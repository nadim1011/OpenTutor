<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'টিউশন বিস্তারিত';
require_once __DIR__ . '/includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT tp.*, u.name AS student_name FROM tuition_posts tp JOIN users u ON u.id = tp.student_id WHERE tp.id = ? LIMIT 1');
$stmt->execute([$id]);
$tuition = $stmt->fetch();

if (!$tuition) {
    $_SESSION['error'] = 'টিউশন পোস্টটি খুঁজে পাওয়া যায়নি।';
    redirect('tuitions.php');
}
?>

<div class="container" style="padding: 3rem 0;">
    <div class="card">
        <h1><?php echo e($tuition['title']); ?></h1>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin: 1rem 0; color: var(--text-muted);">
            <span><i class="fas fa-user-graduate"></i> <?php echo e($tuition['class_level']); ?></span>
            <span><i class="fas fa-book"></i> <?php echo e($tuition['subjects']); ?></span>
            <span><i class="fas fa-map-marker-alt"></i> <?php echo e($tuition['location']); ?></span>
            <span><i class="fas fa-money-bill-wave"></i> <?php echo format_bdt($tuition['salary'] ?? 0); ?></span>
        </div>

        <p><?php echo nl2br(e($tuition['description'])); ?></p>

        <div style="margin-top: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap: 1rem;">
            <div class="card" style="background: #f8fafc;"><strong>সপ্তাহে দিন:</strong> <?php echo e($tuition['days_per_week']); ?></div>
            <div class="card" style="background: #f8fafc;"><strong>পছন্দের সময়:</strong> <?php echo e($tuition['preferred_time']); ?></div>
            <div class="card" style="background: #f8fafc;"><strong>শিক্ষকের লিঙ্গ:</strong> <?php echo $tuition['gender_preference'] === 'ANY' ? 'যেকোনো' : e($tuition['gender_preference']); ?></div>
            <div class="card" style="background: #f8fafc;"><strong>শিখন মোড:</strong> <?php echo e($tuition['learning_mode']); ?></div>
        </div>

        <div style="margin-top: 2rem;">
            <?php if (isLoggedIn() && $_SESSION['user_role'] === 'TUTOR'): ?>
                <a href="tutor/apply.php?id=<?php echo (int)$tuition['id']; ?>" class="btn btn-primary">আবেদন করুন</a>
            <?php elseif (!isLoggedIn()): ?>
                <a href="login.php" class="btn btn-primary">লগইন করে আবেদন করুন</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
