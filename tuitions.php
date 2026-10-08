<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'টিউশন খুঁজুন';
require_once __DIR__ . '/includes/header.php';

$subject = $_GET['subject'] ?? '';
$class = $_GET['class'] ?? '';
$location = $_GET['location'] ?? '';

$query = "SELECT tp.*, u.name AS student_name FROM tuition_posts tp JOIN users u ON tp.student_id = u.id WHERE tp.status = 'OPEN'";
$params = [];

if (!empty($subject)) {
    $query .= ' AND tp.subjects LIKE ?';
    $params[] = "%$subject%";
}

if (!empty($class)) {
    $query .= ' AND tp.class_level LIKE ?';
    $params[] = "%$class%";
}

if (!empty($location)) {
    $query .= ' AND tp.location LIKE ?';
    $params[] = "%$location%";
}

$query .= ' ORDER BY tp.created_at DESC';
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$tuitions = $stmt->fetchAll();
?>

<div class="container" style="padding: 3rem 0;">
    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 2rem;">
        <aside>
            <div class="card">
                <h3>ফিল্টার করুন</h3>
                <form action="tuitions.php" method="GET" style="margin-top: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">বিষয়</label>
                        <input type="text" name="subject" class="form-control" value="<?php echo e($subject); ?>" placeholder="যেমন: গণিত">
                    </div>
                    <div class="form-group">
                        <label class="form-label">শ্রেণি</label>
                        <input type="text" name="class" class="form-control" value="<?php echo e($class); ?>" placeholder="যেমন: নবম">
                    </div>
                    <div class="form-group">
                        <label class="form-label">এলাকা</label>
                        <input type="text" name="location" class="form-control" value="<?php echo e($location); ?>" placeholder="যেমন: ঢাকা">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">খুঁজুন</button>
                </form>
            </div>
        </aside>

        <section>
            <h2 style="margin-bottom: 2rem;">টিউশন পোস্টসমূহ (<?php echo count($tuitions); ?>)</h2>

            <?php if (empty($tuitions)): ?>
                <div class="card" style="text-align: center; padding: 3rem;">
                    <i class="fas fa-search" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                    <p>দুঃখিত, কোনো টিউশন পোস্ট পাওয়া যায়নি।</p>
                </div>
            <?php else: ?>
                <div style="display: grid; gap: 1.5rem;">
                    <?php foreach ($tuitions as $t): ?>
                        <div class="card tuition-card">
                            <div style="display: flex; justify-content: space-between; align-items: start; gap: 1rem; flex-wrap: wrap;">
                                <div>
                                    <h3 style="margin-bottom: 0.5rem; color: var(--primary-color);"><?php echo e($t['title']); ?></h3>
                                    <div style="display: flex; gap: 1rem; flex-wrap: wrap; font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">
                                        <span><i class="fas fa-book"></i> <?php echo e($t['subjects']); ?></span>
                                        <span><i class="fas fa-user-graduate"></i> <?php echo e($t['class_level']); ?></span>
                                        <span><i class="fas fa-map-marker-alt"></i> <?php echo e($t['location']); ?></span>
                                        <span><i class="fas fa-calendar-alt"></i> সপ্তাহে <?php echo e($t['days_per_week']); ?> দিন</span>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-weight: 700; font-size: 1.2rem; color: var(--secondary-color);"><?php echo format_bdt($t['salary']); ?>/মাস</div>
                                    <div style="font-size: 0.8rem; color: var(--text-muted);"><?php echo date('d M, Y', strtotime($t['created_at'])); ?></div>
                                </div>
                            </div>

                            <p style="margin-bottom: 1.5rem; font-size: 0.95rem; line-height: 1.5;">
                                <?php echo e(mb_strimwidth($t['description'], 0, 200, '...')); ?>
                            </p>

                            <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
                                <div style="font-size: 0.9rem;">
                                    <strong>শিক্ষক পছন্দ:</strong>
                                    <?php echo $t['gender_preference'] === 'MALE' ? 'পুরুষ' : ($t['gender_preference'] === 'FEMALE' ? 'মহিলা' : 'যেকোনো'); ?>
                                </div>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <a href="tuition-details.php?id=<?php echo (int)$t['id']; ?>" class="btn btn-outline" style="font-size: 0.9rem;">বিস্তারিত দেখুন</a>
                                    <?php if (isLoggedIn() && $_SESSION['user_role'] === 'TUTOR'): ?>
                                        <a href="tutor/apply.php?id=<?php echo (int)$t['id']; ?>" class="btn btn-primary" style="font-size: 0.9rem;">আবেদন করুন</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
