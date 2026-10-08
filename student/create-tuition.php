<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('STUDENT');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'সুরক্ষিত ফর্মের জন্য টোকেন ভুল হয়েছে।';
    } else {
        $title = trim($_POST['title'] ?? '');
        $class = trim($_POST['class_level'] ?? '');
        $subjects = trim($_POST['subjects'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $salary = trim($_POST['salary'] ?? '');
        $days = (int)($_POST['days_per_week'] ?? 0);
        $time = trim($_POST['preferred_time'] ?? '');
        $gender = $_POST['gender_preference'] ?? 'ANY';
        $mode = $_POST['learning_mode'] ?? 'OFFLINE';
        $desc = trim($_POST['description'] ?? '');

        $errors = [];
        if ($title === '') $errors[] = 'টিউশনের শিরোনাম দিন।';
        if ($class === '') $errors[] = 'শ্রেণি লিখুন।';
        if ($subjects === '') $errors[] = 'বিষয় লিখুন।';
        if ($location === '') $errors[] = 'এলাকা লিখুন।';
        if ($salary === '' || !is_numeric($salary)) $errors[] = 'বেতন সঠিকভাবে লিখুন।';
        if ($days < 1 || $days > 7) $errors[] = 'সপ্তাহে দিন ১ থেকে ৭ এর মধ্যে হতে হবে।';
        if ($time === '') $errors[] = 'পছন্দের সময় লিখুন।';
        if ($desc === '') $errors[] = 'বিস্তারিত লিখুন।';

        if (empty($errors)) {
            $stmt = $pdo->prepare('INSERT INTO tuition_posts (student_id, title, class_level, subjects, location, salary, days_per_week, preferred_time, gender_preference, learning_mode, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$_SESSION['user_id'], $title, $class, $subjects, $location, $salary, $days, $time, $gender, $mode, $desc]);
            $_SESSION['success'] = 'আপনার টিউশন পোস্ট সফলভাবে প্রকাশিত হয়েছে।';
            redirect('my-tuitions.php');
        } else {
            $_SESSION['error'] = implode('<br>', $errors);
        }
    }
}
require_once __DIR__ . '/layout-header.php';
?>

<h2>নতুন টিউশন পোস্ট করুন</h2>
<form action="create-tuition.php" method="POST">
    <?php echo csrf_input(); ?>
    <div class="form-group">
        <label class="form-label">টিউশনের শিরোনাম</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="form-group">
        <label class="form-label">শিক্ষার্থীর শ্রেণি</label>
        <input type="text" name="class_level" class="form-control" required>
    </div>
    <div class="form-group">
        <label class="form-label">বিষয়</label>
        <input type="text" name="subjects" class="form-control" required>
    </div>
    <div class="form-group">
        <label class="form-label">এলাকা</label>
        <input type="text" name="location" class="form-control" required>
    </div>
    <div class="form-group">
        <label class="form-label">বেতন (৳)</label>
        <input type="number" name="salary" class="form-control" required>
    </div>
    <div class="form-group">
        <label class="form-label">সপ্তাহে কত দিন</label>
        <input type="number" name="days_per_week" min="1" max="7" class="form-control" required>
    </div>
    <div class="form-group">
        <label class="form-label">পছন্দের সময়</label>
        <input type="text" name="preferred_time" class="form-control" required>
    </div>
    <div class="form-group">
        <label class="form-label">শিক্ষকের লিঙ্গ</label>
        <select name="gender_preference" class="form-control">
            <option value="ANY">যেকোনো</option>
            <option value="MALE">পুরুষ</option>
            <option value="FEMALE">মহিলা</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">অনলাইন/অফলাইন</label>
        <select name="learning_mode" class="form-control">
            <option value="OFFLINE">অফলাইন</option>
            <option value="ONLINE">অনলাইন</option>
            <option value="BOTH">উভয়</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">বিশেষ প্রয়োজনীয়তা</label>
        <input type="text" name="special_requirements" class="form-control" placeholder="ঐচ্ছিক">
    </div>
    <div class="form-group">
        <label class="form-label">বিস্তারিত</label>
        <textarea name="description" class="form-control" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">পাবলিশ করুন</button>
</form>

<?php require_once __DIR__ . '/layout-footer.php'; ?>
