<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'লগইন';
require_once __DIR__ . '/includes/header.php';

redirect_if_logged_in();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'সুরক্ষিত ফর্মের জন্য টোকেন ভুল হয়েছে।';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors = [];

        if (empty($email)) $errors[] = 'ইমেইল দিন।';
        if (empty($password)) $errors[] = 'পাসওয়ার্ড দিন।';

        if (empty($errors)) {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? OR phone = ? LIMIT 1');
            $stmt->execute([$email, $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] === 'SUSPENDED') {
                    $errors[] = 'আপনার অ্যাকাউন্টটি স্থগিত করা হয়েছে।';
                } else {
                    $_SESSION['user_id'] = (int)$user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['success'] = 'সফলভাবে লগইন করেছেন। স্বাগতম, ' . $user['name'] . '!';

                    if ($user['role'] === 'ADMIN') {
                        redirect('admin/index.php');
                    } elseif ($user['role'] === 'TUTOR') {
                        redirect('tutor/index.php');
                    } else {
                        redirect('student/index.php');
                    }
                }
            } else {
                $errors[] = 'ইমেইল বা পাসওয়ার্ড ভুল।';
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
        }
    }
}
?>

<div class="container" style="max-width: 450px; margin-top: 5rem;">
    <div class="card">
        <h2 style="text-align: center; margin-bottom: 2rem;">লগইন করুন</h2>

        <form action="login.php" method="POST">
            <?php echo csrf_input(); ?>

            <div class="form-group">
                <label class="form-label">ইমেইল বা ফোন নম্বর</label>
                <input type="text" name="email" class="form-control" required placeholder="আপনার ইমেইল বা ফোন দিন">
            </div>

            <div class="form-group">
                <label class="form-label">পাসওয়ার্ড</label>
                <input type="password" name="password" class="form-control" required placeholder="আপনার পাসওয়ার্ড">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">লগইন</button>
        </form>

        <p style="text-align: center; margin-top: 1.5rem;">
            অ্যাকাউন্ট নেই? <a href="register.php" style="color: var(--primary-color); font-weight: 600;">নিবন্ধন করুন</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
