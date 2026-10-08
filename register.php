<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'নিবন্ধন';
require_once __DIR__ . '/includes/header.php';

redirect_if_logged_in();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'সুরক্ষিত ফর্মের জন্য টোকেন ভুল হয়েছে।';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $role = $_POST['role'] ?? 'STUDENT';

        $errors = [];

        if (empty($name)) $errors[] = 'নাম আবশ্যক।';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'সঠিক ইমেইল দিন।';
        if (empty($phone)) $errors[] = 'ফোন নম্বর আবশ্যক।';
        if (strlen($password) < 6) $errors[] = 'পাসওয়ার্ড অন্তত ৬ অক্ষরের হতে হবে।';
        if ($password !== $confirm_password) $errors[] = 'পাসওয়ার্ড দুটি মেলেনি।';
        if (!in_array($role, ['STUDENT', 'TUTOR'])) $errors[] = 'সঠিক রোল নির্বাচন করুন।';

        if (empty($errors)) {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? OR phone = ? LIMIT 1');
            $stmt->execute([$email, $phone]);
            if ($stmt->fetch()) {
                $errors[] = 'এই ইমেইল বা ফোন নম্বরটি ইতিমধ্যে ব্যবহৃত হয়েছে।';
            } else {
                try {
                    $pdo->beginTransaction();
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare('INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)');
                    $stmt->execute([$name, $email, $phone, $hashed, $role]);
                    $userId = $pdo->lastInsertId();

                    if ($role === 'STUDENT') {
                        $pdo->prepare('INSERT INTO student_profiles (user_id) VALUES (?)')->execute([$userId]);
                    } else {
                        $pdo->prepare('INSERT INTO tutor_profiles (user_id) VALUES (?)')->execute([$userId]);
                    }

                    $pdo->commit();
                    $_SESSION['success'] = 'নিবন্ধন সফল হয়েছে! এখন লগইন করুন।';
                    redirect('login.php');
                } catch (Exception $e) {
                    $pdo->rollBack();
                    $errors[] = 'কিছু সমস্যা হয়েছে। আবার চেষ্টা করুন।';
                }
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
        }
    }
}
?>

<div class="container" style="max-width: 500px; margin-top: 3rem;">
    <div class="card">
        <h2 style="text-align: center; margin-bottom: 2rem;">নতুন অ্যাকাউন্ট তৈরি করুন</h2>

        <form action="register.php" method="POST">
            <?php echo csrf_input(); ?>

            <div class="form-group">
                <label class="form-label">আপনার নাম</label>
                <input type="text" name="name" class="form-control" required placeholder="আপনার পূর্ণ নাম লিখুন">
            </div>

            <div class="form-group">
                <label class="form-label">ইমেইল ঠিকানা</label>
                <input type="email" name="email" class="form-control" required placeholder="example@mail.com">
            </div>

            <div class="form-group">
                <label class="form-label">ফোন নম্বর</label>
                <input type="text" name="phone" class="form-control" required placeholder="০১৭XXXXXXXX">
            </div>

            <div class="form-group">
                <label class="form-label">আপনি কি হিসেবে নিবন্ধন করতে চান?</label>
                <select name="role" class="form-control" required>
                    <option value="STUDENT">শিক্ষার্থী/অভিভাবক</option>
                    <option value="TUTOR">শিক্ষক/টিউটর</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">পাসওয়ার্ড</label>
                <input type="password" name="password" class="form-control" required placeholder="কমপক্ষে ৬ অক্ষর">
            </div>

            <div class="form-group">
                <label class="form-label">পাসওয়ার্ড নিশ্চিত করুন</label>
                <input type="password" name="confirm_password" class="form-control" required placeholder="আবার পাসওয়ার্ডটি লিখুন">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">নিবন্ধন করুন</button>
        </form>

        <p style="text-align: center; margin-top: 1.5rem;">
            ইতিমধ্যে অ্যাকাউন্ট আছে? <a href="login.php" style="color: var(--primary-color); font-weight: 600;">লগইন করুন</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
