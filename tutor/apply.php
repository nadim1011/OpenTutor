<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('TUTOR');

$tutionId = (int)($_GET['id'] ?? 0);
if ($tutionId <= 0) {
    $_SESSION['error'] = 'টিউশন নির্বাচন করুন।';
    redirect('tuition-search.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'সুরক্ষিত ফর্মের জন্য টোকেন ভুল হয়েছে।';
    } else {
        $expected = trim($_POST['expected_salary'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $experience = trim($_POST['experience'] ?? '');
        $check = $pdo->prepare('SELECT id FROM applications WHERE tuition_id = ? AND tutor_id = ? LIMIT 1');
        $check->execute([$tutionId, $_SESSION['user_id']]);
        if ($check->fetch()) {
            $_SESSION['error'] = 'আপনি এই টিউশনে ইতিমধ্যেই আবেদন করেছেন।';
        } else {
            $stmt = $pdo->prepare('INSERT INTO applications (tuition_id, tutor_id, expected_salary, message, experience_details) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$tutionId, $_SESSION['user_id'], $expected, $message, $experience]);
            $_SESSION['success'] = 'আবেদন সফলভাবে জমা হয়েছে।';
            redirect('applications.php');
        }
    }
}
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container" style="padding:2rem 0; max-width:700px;">
    <div class="card">
        <h2>টিউশন আবেদন</h2>
        <form action="apply.php?id=<?php echo (int)$tutionId; ?>" method="POST">
            <?php echo csrf_input(); ?>
            <div class="form-group"><label class="form-label">প্রত্যাশিত বেতন</label><input type="number" name="expected_salary" class="form-control" required></div>
            <div class="form-group"><label class="form-label">নিজের সম্পর্কে</label><textarea name="message" class="form-control" rows="4" required></textarea></div>
            <div class="form-group"><label class="form-label">কেন আপনি এই টিউশনের জন্য উপযুক্ত?</label><textarea name="experience" class="form-control" rows="4" required></textarea></div>
            <button type="submit" class="btn btn-primary">আবেদন জমা দিন</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
