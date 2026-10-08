<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('ADMIN');
require_once __DIR__ . '/../includes/header.php';

$counts = [
    'users' => $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
    'tutors' => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'TUTOR'")->fetchColumn(),
    'posts' => $pdo->query('SELECT COUNT(*) FROM tuition_posts')->fetchColumn(),
    'reports' => $pdo->query("SELECT COUNT(*) FROM reports WHERE status = 'PENDING'")->fetchColumn(),
];
?>

<div class="container" style="padding:2rem 0;">
    <h2>অ্যাডমিন ড্যাশবোর্ড</h2>
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap:1.5rem; margin-top:1.5rem;">
        <div class="card"><h3>মোট ব্যবহারকারী</h3><p style="font-size:2rem; font-weight:700; color:var(--primary-color);"><?php echo (int)$counts['users']; ?></p></div>
        <div class="card"><h3>মোট শিক্ষক</h3><p style="font-size:2rem; font-weight:700; color:var(--primary-color);"><?php echo (int)$counts['tutors']; ?></p></div>
        <div class="card"><h3>টিউশন পোস্ট</h3><p style="font-size:2rem; font-weight:700; color:var(--primary-color);"><?php echo (int)$counts['posts']; ?></p></div>
        <div class="card"><h3>পেন্ডিং রিপোর্ট</h3><p style="font-size:2rem; font-weight:700; color:var(--primary-color);"><?php echo (int)$counts['reports']; ?></p></div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
