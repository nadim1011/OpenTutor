<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'শিক্ষক খুঁজুন';

$subject = $_GET['subject'] ?? '';
$class = $_GET['class'] ?? '';
$location = $_GET['location'] ?? '';
$gender = $_GET['gender'] ?? '';

$returnUrl = 'teachers.php' . (!empty($_GET) ? '?' . http_build_query($_GET) : '');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_tutor_id'])) {
    if (!isLoggedIn() || $_SESSION['user_role'] !== 'STUDENT') {
        $_SESSION['error'] = 'শুধুমাত্র শিক্ষার্থীরা পছন্দের শিক্ষক যোগ করতে পারবেন।';
        redirect($returnUrl);
    }

    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'সুরক্ষিত ফর্মের জন্য টোকেন ভুল হয়েছে।';
        redirect($returnUrl);
    }

    $tutorId = (int)$_POST['favorite_tutor_id'];
    $tutorCheck = $pdo->prepare("SELECT id FROM users WHERE id = ? AND role = 'TUTOR' AND status = 'ACTIVE' LIMIT 1");
    $tutorCheck->execute([$tutorId]);
    if (!$tutorCheck->fetch()) {
        $_SESSION['error'] = 'শিক্ষকের প্রোফাইল খুঁজে পাওয়া যায়নি।';
        redirect($returnUrl);
    }

    $favoriteCheck = $pdo->prepare("SELECT id FROM shortlists WHERE user_id = ? AND target_type = 'TUTOR' AND target_id = ? LIMIT 1");
    $favoriteCheck->execute([$_SESSION['user_id'], $tutorId]);
    if ($favoriteCheck->fetch()) {
        $pdo->prepare("DELETE FROM shortlists WHERE user_id = ? AND target_type = 'TUTOR' AND target_id = ?")
            ->execute([$_SESSION['user_id'], $tutorId]);
        $_SESSION['success'] = 'শিক্ষককে পছন্দের তালিকা থেকে সরানো হয়েছে।';
    } else {
        $pdo->prepare("INSERT INTO shortlists (user_id, target_type, target_id) VALUES (?, 'TUTOR', ?)")
            ->execute([$_SESSION['user_id'], $tutorId]);
        $_SESSION['success'] = 'শিক্ষককে পছন্দের তালিকায় যোগ করা হয়েছে।';
    }
    redirect($returnUrl);
}

$currentStudentId = isLoggedIn() && $_SESSION['user_role'] === 'STUDENT' ? (int)$_SESSION['user_id'] : 0;
$query = "SELECT u.id AS user_id, u.name, u.profile_image, tp.*,
                 EXISTS (SELECT 1 FROM shortlists s WHERE s.user_id = ? AND s.target_type = 'TUTOR' AND s.target_id = u.id) AS is_favorite
          FROM users u JOIN tutor_profiles tp ON u.id = tp.user_id
          WHERE u.role = 'TUTOR' AND u.status = 'ACTIVE'";
$params = [$currentStudentId];

if (!empty($subject)) {
    $query .= ' AND (tp.bio LIKE ? OR tp.education LIKE ? OR tp.university LIKE ?)';
    $params[] = "%$subject%";
    $params[] = "%$subject%";
    $params[] = "%$subject%";
}

if (!empty($location)) {
    $query .= ' AND tp.location LIKE ?';
    $params[] = "%$location%";
}

if (!empty($gender)) {
    $query .= ' AND tp.gender = ?';
    $params[] = $gender;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$tutors = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 3rem 0;">
    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 2rem;">
        <aside>
            <div class="card">
                <h3>ফিল্টার করুন</h3>
                <form action="teachers.php" method="GET" style="margin-top: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">বিষয়</label>
                        <input type="text" name="subject" class="form-control" value="<?php echo e($subject); ?>" placeholder="যেমন: গণিত">
                    </div>
                    <div class="form-group">
                        <label class="form-label">এলাকা</label>
                        <input type="text" name="location" class="form-control" value="<?php echo e($location); ?>" placeholder="যেমন: ঢাকা">
                    </div>
                    <div class="form-group">
                        <label class="form-label">লিঙ্গ</label>
                        <select name="gender" class="form-control">
                            <option value="">সবাই</option>
                            <option value="MALE" <?php if ($gender === 'MALE') echo 'selected'; ?>>পুরুষ</option>
                            <option value="FEMALE" <?php if ($gender === 'FEMALE') echo 'selected'; ?>>মহিলা</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">ফিল্টার করুন</button>
                </form>
            </div>
        </aside>

        <section>
            <h2 style="margin-bottom: 2rem;">শিক্ষকবৃন্দ (<?php echo count($tutors); ?>)</h2>

            <?php if (empty($tutors)): ?>
                <div class="card" style="text-align: center; padding: 3rem;">
                    <i class="fas fa-search" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                    <p>দুঃখিত, আপনার খোঁজা অনুযায়ী কোনো শিক্ষক পাওয়া যায়নি।</p>
                </div>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                    <?php foreach ($tutors as $tutor): ?>
                        <div class="card tutor-card">
                            <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
                                <img src="<?php echo !empty($tutor['profile_image']) ? BASE_URL . $tutor['profile_image'] : 'https://ui-avatars.com/api/?name=' . urlencode($tutor['name']); ?>" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                                <div>
                                    <h4 style="margin-bottom: 0.2rem;"><?php echo e($tutor['name']); ?></h4>
                                    <?php if ($tutor['verification_status'] === 'VERIFIED'): ?>
                                        <span style="font-size: 0.8rem; color: var(--primary-color); font-weight: 600;">
                                            <i class="fas fa-check-circle"></i> যাচাইকৃত শিক্ষক
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem; min-height: 3.6rem; overflow: hidden;">
                                <?php echo e($tutor['bio'] ?: 'শিক্ষকের প্রোফাইল এখনো পূর্ণ করা হয়নি।'); ?>
                            </p>

                            <div style="font-size: 0.9rem; margin-bottom: 1.5rem;">
                                <div><i class="fas fa-graduation-cap" style="width: 20px; color: var(--primary-color);"></i> <?php echo e($tutor['university'] ?: 'তথ্য নেই'); ?></div>
                                <div><i class="fas fa-map-marker-alt" style="width: 20px; color: var(--primary-color);"></i> <?php echo e($tutor['location'] ?: 'তথ্য নেই'); ?></div>
                                <div><i class="fas fa-money-bill-wave" style="width: 20px; color: var(--primary-color);"></i> <?php echo format_bdt($tutor['expected_salary_min'] ?? 0); ?> - <?php echo format_bdt($tutor['expected_salary_max'] ?? 0); ?></div>
                            </div>

                            <div style="display: flex; gap: 0.5rem;">
                                <a href="teacher-details.php?id=<?php echo (int)$tutor['user_id']; ?>" class="btn btn-outline" style="flex: 1; text-align: center; font-size: 0.9rem;">প্রোফাইল দেখুন</a>
                                <?php if ($currentStudentId > 0): ?>
                                    <form action="<?php echo e($returnUrl); ?>" method="POST">
                                        <?php echo csrf_input(); ?>
                                        <input type="hidden" name="favorite_tutor_id" value="<?php echo (int)$tutor['user_id']; ?>">
                                        <button type="submit" class="btn btn-secondary" aria-label="<?php echo $tutor['is_favorite'] ? 'পছন্দের তালিকা থেকে সরান' : 'পছন্দের তালিকায় যোগ করুন'; ?>" title="<?php echo $tutor['is_favorite'] ? 'পছন্দের তালিকা থেকে সরান' : 'পছন্দের তালিকায় যোগ করুন'; ?>">
                                            <i class="<?php echo $tutor['is_favorite'] ? 'fas' : 'far'; ?> fa-heart"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
