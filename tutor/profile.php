<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('TUTOR');

$user = currentUser($pdo);
$stmt = $pdo->prepare('SELECT * FROM tutor_profiles WHERE user_id = ? LIMIT 1');
$stmt->execute([$_SESSION['user_id']]);
$profile = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $education = trim($_POST['education'] ?? '');
    $university = trim($_POST['university'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $salaryMin = (float)($_POST['expected_salary_min'] ?? 0);
    $salaryMax = (float)($_POST['expected_salary_max'] ?? 0);
    $gender = $_POST['gender'] ?? 'OTHER';

    if ($profile) {
        $stmt = $pdo->prepare('UPDATE tutor_profiles SET education = ?, university = ?, department = ?, experience = ?, bio = ?, location = ?, expected_salary_min = ?, expected_salary_max = ?, gender = ? WHERE user_id = ?');
        $stmt->execute([$education, $university, $department, $experience, $bio, $location, $salaryMin, $salaryMax, $gender, $_SESSION['user_id']]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO tutor_profiles (user_id, education, university, department, experience, bio, location, expected_salary_min, expected_salary_max, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$_SESSION['user_id'], $education, $university, $department, $experience, $bio, $location, $salaryMin, $salaryMax, $gender]);
    }
    $_SESSION['success'] = 'আপনার প্রোফাইল আপডেট হয়েছে।';
    redirect('profile.php');
}
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container" style="padding:2rem 0;">
    <h2>আমার প্রোফাইল</h2>
    <form action="profile.php" method="POST" class="card" style="margin-top: 1rem;">
        <?php echo csrf_input(); ?>
        <div class="form-group"><label class="form-label">শিক্ষাগত যোগ্যতা</label><input type="text" name="education" class="form-control" value="<?php echo e($profile['education'] ?? ''); ?>"></div>
        <div class="form-group"><label class="form-label">বিশ্ববিদ্যালয়</label><input type="text" name="university" class="form-control" value="<?php echo e($profile['university'] ?? ''); ?>"></div>
        <div class="form-group"><label class="form-label">বিভাগ</label><input type="text" name="department" class="form-control" value="<?php echo e($profile['department'] ?? ''); ?>"></div>
        <div class="form-group"><label class="form-label">অভিজ্ঞতা</label><input type="text" name="experience" class="form-control" value="<?php echo e($profile['experience'] ?? ''); ?>"></div>
        <div class="form-group"><label class="form-label">এলাকা</label><input type="text" name="location" class="form-control" value="<?php echo e($profile['location'] ?? ''); ?>"></div>
        <div class="form-group"><label class="form-label">প্রত্যাশিত বেতন (সর্বনিম্ন)</label><input type="number" name="expected_salary_min" class="form-control" value="<?php echo e($profile['expected_salary_min'] ?? ''); ?>"></div>
        <div class="form-group"><label class="form-label">প্রত্যাশিত বেতন (সর্বোচ্চ)</label><input type="number" name="expected_salary_max" class="form-control" value="<?php echo e($profile['expected_salary_max'] ?? ''); ?>"></div>
        <div class="form-group"><label class="form-label">জেন্ডার</label><select name="gender" class="form-control"><option value="MALE" <?php if (($profile['gender'] ?? '') === 'MALE') echo 'selected'; ?>>পুরুষ</option><option value="FEMALE" <?php if (($profile['gender'] ?? '') === 'FEMALE') echo 'selected'; ?>>মহিলা</option><option value="OTHER" <?php if (($profile['gender'] ?? '') === 'OTHER' || empty($profile['gender'])) echo 'selected'; ?>>অন্যান্য</option></select></div>
        <div class="form-group"><label class="form-label">নিজের সম্পর্কে</label><textarea name="bio" class="form-control" rows="5"><?php echo e($profile['bio'] ?? ''); ?></textarea></div>
        <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
