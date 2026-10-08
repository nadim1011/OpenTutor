<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'শিক্ষক প্রোফাইল';
require_once __DIR__ . '/includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT u.*, tp.* FROM users u JOIN tutor_profiles tp ON tp.user_id = u.id WHERE u.id = ? AND u.role = ? LIMIT 1');
$stmt->execute([$id, 'TUTOR']);
$tutor = $stmt->fetch();

if (!$tutor) {
    $_SESSION['error'] = 'শিক্ষকের প্রোফাইল খুঁজে পাওয়া যায়নি।';
    redirect('teachers.php');
}
?>

<div class="container" style="padding: 3rem 0;">
    <div class="card">
        <div style="display: flex; gap: 2rem; flex-wrap: wrap; align-items: center;">
            <img src="<?php echo !empty($tutor['profile_image']) ? BASE_URL . $tutor['profile_image'] : 'https://ui-avatars.com/api/?name=' . urlencode($tutor['name']); ?>" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover;">
            <div>
                <h1><?php echo e($tutor['name']); ?></h1>
                <?php if ($tutor['verification_status'] === 'VERIFIED'): ?>
                    <p style="color: var(--primary-color); font-weight: 700;"><i class="fas fa-check-circle"></i> যাচাইকৃত শিক্ষক</p>
                <?php endif; ?>
                <p><?php echo e($tutor['bio'] ?: 'এই শিক্ষক সম্পর্কে বিস্তারিত তথ্য প্রদান করা হয়নি।'); ?></p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-top: 2rem;">
            <div class="card" style="background: #f8fafc;"><strong>বিশ্ববিদ্যালয়:</strong> <?php echo e($tutor['university'] ?: 'তথ্য নেই'); ?></div>
            <div class="card" style="background: #f8fafc;"><strong>বিভাগ:</strong> <?php echo e($tutor['department'] ?: 'তথ্য নেই'); ?></div>
            <div class="card" style="background: #f8fafc;"><strong>শিক্ষাগত যোগ্যতা:</strong> <?php echo e($tutor['education'] ?: 'তথ্য নেই'); ?></div>
            <div class="card" style="background: #f8fafc;"><strong>অভিজ্ঞতা:</strong> <?php echo e($tutor['experience'] ?: 'তথ্য নেই'); ?></div>
            <div class="card" style="background: #f8fafc;"><strong>এলাকা:</strong> <?php echo e($tutor['location'] ?: 'তথ্য নেই'); ?></div>
            <div class="card" style="background: #f8fafc;"><strong>আশাকৃত বেতন:</strong> <?php echo format_bdt($tutor['expected_salary_min'] ?? 0); ?> - <?php echo format_bdt($tutor['expected_salary_max'] ?? 0); ?></div>
        </div>

        <div style="margin-top: 2rem;">
            <a href="tuitions.php" class="btn btn-outline">টিউশন পোস্ট দেখুন</a>
            <?php if (isLoggedIn() && $_SESSION['user_role'] === 'STUDENT'): ?>
                <a href="student/create-tuition.php" class="btn btn-primary">টিউশন প্রয়োজন লিখুন</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
