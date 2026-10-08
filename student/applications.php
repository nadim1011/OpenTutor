<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('STUDENT');

$stmt = $pdo->prepare('SELECT a.*, tp.title, u.id AS tutor_id, u.name AS tutor_name
                       FROM applications a
                       JOIN tuition_posts tp ON tp.id = a.tuition_id
                       JOIN users u ON u.id = a.tutor_id
                       WHERE tp.student_id = ?
                       ORDER BY a.created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$applications = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $appId = (int)($_POST['application_id'] ?? 0);
    $status = $_POST['status'] ?? 'REJECTED';
    $pdo->prepare('UPDATE applications SET status = ? WHERE id = ?')->execute([$status, $appId]);
    $_SESSION['success'] = 'অবস্থা আপডেট করা হয়েছে।';
    redirect('applications.php');
}
require_once __DIR__ . '/layout-header.php';
?>

<h2>আবেদনসমূহ</h2>
<?php if (empty($applications)): ?>
    <div class="card"><p>এখনও কোনো আবেদন নেই।</p></div>
<?php else: ?>
    <div style="display: grid; gap: 1rem;">
        <?php foreach ($applications as $application): ?>
            <div class="card">
                <h3><?php echo e($application['title']); ?></h3>
                <p>শিক্ষক: <?php echo e($application['tutor_name']); ?></p>
                <p>প্রত্যাশিত বেতন: <?php echo format_bdt($application['expected_salary']); ?></p>
                <p>নিজের সম্পর্কে: <?php echo nl2br(e($application['message'])); ?></p>
                <p>অভিজ্ঞতা ও উপযুক্ততা: <?php echo nl2br(e($application['experience_details'])); ?></p>
                <a href="../teacher-details.php?id=<?php echo (int)$application['tutor_id']; ?>" class="btn btn-outline">শিক্ষকের সম্পূর্ণ প্রোফাইল দেখুন</a>
                <p>অবস্থা: <span class="badge"><?php echo pretty_status($application['status']); ?></span></p>
                <form action="applications.php" method="POST" style="margin-top: 1rem;">
                    <?php echo csrf_input(); ?>
                    <input type="hidden" name="application_id" value="<?php echo (int)$application['id']; ?>">
                    <select name="status" class="form-control" style="max-width: 200px; display: inline-block;">
                        <option value="ACCEPTED">গৃহীত</option>
                        <option value="REJECTED">বাতিল</option>
                    </select>
                    <button type="submit" name="action" value="update" class="btn btn-primary">আপডেট</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/layout-footer.php'; ?>
