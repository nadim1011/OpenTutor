<?php
// opentutor/includes/header.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';

$dashboardRole = $_SESSION['user_role'] ?? '';
$dashboardScript = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$isDashboardPage = in_array($dashboardRole, ['STUDENT', 'TUTOR', 'ADMIN'], true)
    && preg_match('#/(student|tutor|admin)/#', $dashboardScript) === 1;
$dashboardUser = $isDashboardPage ? currentUser($pdo) : null;
$stylePath = __DIR__ . '/../assets/css/style.css';
$styleVersion = is_file($stylePath) ? (string) filemtime($stylePath) : '1';

$dashboardLinks = [
    'STUDENT' => [
        ['index.php', 'ওভারভিউ', 'fa-th-large'],
        ['profile.php', 'প্রোফাইল', 'fa-user'],
        ['create-tuition.php', 'টিউশন পোস্ট', 'fa-plus-circle'],
        ['my-tuitions.php', 'আমার টিউশন', 'fa-list'],
        ['applications.php', 'আবেদনসমূহ', 'fa-paper-plane'],
        ['shortlist.php', 'পছন্দের শিক্ষক', 'fa-heart'],
        ['reviews.php', 'রিভিউ', 'fa-star'],
        ['settings.php', 'সেটিংস', 'fa-cog'],
    ],
    'TUTOR' => [
        ['index.php', 'ওভারভিউ', 'fa-th-large'],
        ['profile.php', 'আমার প্রোফাইল', 'fa-user'],
        ['tuition-search.php', 'টিউশন খুঁজুন', 'fa-search'],
        ['applications.php', 'আমার আবেদন', 'fa-paper-plane'],
        ['shortlist.php', 'পছন্দের টিউশন', 'fa-heart'],
        ['reviews.php', 'রিভিউ', 'fa-star'],
        ['settings.php', 'সেটিংস', 'fa-cog'],
    ],
    'ADMIN' => [
        ['index.php', 'ওভারভিউ', 'fa-th-large'],
        ['users.php', 'ব্যবহারকারী', 'fa-users'],
        ['verify-tutors.php', 'শিক্ষক যাচাই', 'fa-user-check'],
        ['tuition-posts.php', 'টিউশন পোস্ট', 'fa-book'],
        ['applications.php', 'আবেদনসমূহ', 'fa-paper-plane'],
        ['reports.php', 'রিপোর্ট', 'fa-flag'],
        ['settings.php', 'সেটিংস', 'fa-cog'],
    ],
];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' | ' . APP_NAME_BN : APP_NAME_BN . ' - ' . APP_TAGLINE; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css?v=<?php echo e($styleVersion); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
<main>
    <div class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success mt-4">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger mt-4">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if ($isDashboardPage): ?>
        <?php
        $dashboardFolder = strtolower($dashboardRole);
        $dashboardCurrentFile = basename($dashboardScript);
        ?>
        <div class="dashboard-layout">
            <aside class="dashboard-sidebar">
                <div class="dashboard-user">
                    <img src="<?php echo !empty($dashboardUser['profile_image']) ? BASE_URL . e($dashboardUser['profile_image']) : 'https://ui-avatars.com/api/?name=' . urlencode($dashboardUser['name'] ?? 'User') . '&background=eff6ff&color=1e3a8a'; ?>"
                         alt="<?php echo e($dashboardUser['name'] ?? ''); ?>">
                    <strong><?php echo e($dashboardUser['name'] ?? ''); ?></strong>
                    <span><?php echo $dashboardRole === 'ADMIN' ? 'অ্যাডমিন' : ($dashboardRole === 'TUTOR' ? 'শিক্ষক' : 'শিক্ষার্থী/অভিভাবক'); ?></span>
                </div>
                <nav class="dashboard-nav" aria-label="ড্যাশবোর্ড মেনু">
                    <?php foreach ($dashboardLinks[$dashboardRole] as [$file, $label, $icon]): ?>
                        <?php $active = $dashboardCurrentFile === $file; ?>
                        <a href="<?php echo BASE_URL . $dashboardFolder . '/' . $file; ?>"
                           class="dashboard-nav-link<?php echo $active ? ' active' : ''; ?>"
                           <?php echo $active ? 'aria-current="page"' : ''; ?>>
                            <i class="fas <?php echo $icon; ?>" aria-hidden="true"></i>
                            <span><?php echo $label; ?></span>
                        </a>
                    <?php endforeach; ?>
                </nav>
                <a class="dashboard-logout" href="<?php echo BASE_URL; ?>logout.php">
                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i><span>লগআউট</span>
                </a>
            </aside>
            <section class="dashboard-content">
    <?php endif; ?>
