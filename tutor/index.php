<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_role('TUTOR');
require_once __DIR__ . '/../includes/header.php';

$user = currentUser($pdo);
$appCount = $pdo->prepare('SELECT COUNT(*) FROM applications WHERE tutor_id = ?');
$appCount->execute([$_SESSION['user_id']]);
$applications = $appCount->fetchColumn();
?>

<div class="container" style="padding: 2rem 0;">
    <h2>শিক্ষক ড্যাশবোর্ড</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap: 1.5rem; margin-top: 2rem;">
        <div class="card"><h3>মোট আবেদন</h3><p style="font-size:2rem; font-weight:700; color:var(--primary-color);"><?php echo (int)$applications; ?></p></div>
        <div class="card"><h3>প্রোফাইল</h3><p>আপনার তথ্য সম্পন্ন করুন</p></div>
        <div class="card"><h3>স্ট্যাটাস</h3><p style="color:var(--secondary-color); font-weight:700;">সক্রিয়</p></div>
    </div>
    <div class="card" style="margin-top: 2rem;">
        <h3>দ্রুত লিংক</h3>
        <div style="display:flex; gap:1rem; flex-wrap:wrap; margin-top:1rem;">
            <a href="profile.php" class="btn btn-outline">আমার প্রোফাইল</a>
            <a href="tuition-search.php" class="btn btn-outline">টিউশন খুঁজুন</a>
            <a href="applications.php" class="btn btn-outline">আমার আবেদন</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
