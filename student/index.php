<?php
// opentutor/student/index.php
$page_title = "ওভারভিউ";
require_once __DIR__ . '/layout-header.php';

// Stats
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tuition_posts WHERE student_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$total_tuitions = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM applications a JOIN tuition_posts tp ON a.tuition_id = tp.id WHERE tp.student_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$total_applications = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM shortlists WHERE user_id = ? AND target_type = 'TUTOR'");
$stmt->execute([$_SESSION['user_id']]);
$total_shortlisted = $stmt->fetchColumn();
?>

<div style="margin-bottom: 2rem;">
    <h2>স্বাগতম, <?php echo e($user['name']); ?>!</h2>
    <p style="color: var(--text-muted);">আপনার ড্যাশবোর্ড থেকে টিউশন ম্যানেজ করুন।</p>
</div>

<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <div class="card" style="text-align: center; border-left: 5px solid var(--primary-color);">
        <h1 style="color: var(--primary-color);"><?php echo $total_tuitions; ?></h1>
        <p style="font-weight: 600;">মোট টিউশন পোস্ট</p>
    </div>
    <div class="card" style="text-align: center; border-left: 5px solid var(--secondary-color);">
        <h1 style="color: var(--secondary-color);"><?php echo $total_applications; ?></h1>
        <p style="font-weight: 600;">মোট আবেদন</p>
    </div>
    <div class="card" style="text-align: center; border-left: 5px solid var(--warning);">
        <h1 style="color: var(--warning);"><?php echo $total_shortlisted; ?></h1>
        <p style="font-weight: 600;">পছন্দের শিক্ষক</p>
    </div>
</div>

<!-- Recent Tuitions -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3>সাম্প্রতিক টিউশন পোস্টসমূহ</h3>
        <a href="my-tuitions.php" style="color: var(--primary-color); font-weight: 600; font-size: 0.9rem;">সব দেখুন</a>
    </div>

    <?php
    $stmt = $pdo->prepare("SELECT * FROM tuition_posts WHERE student_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$_SESSION['user_id']]);
    $recent_tuitions = $stmt->fetchAll();
    ?>

    <?php if (empty($recent_tuitions)): ?>
        <p style="text-align: center; color: var(--text-muted); padding: 2rem;">আপনার কোনো টিউশন পোস্ট নেই।</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color); text-align: left;">
                    <th style="padding: 1rem 0;">শিরোনাম</th>
                    <th style="padding: 1rem 0;">বিষয়</th>
                    <th style="padding: 1rem 0;">স্ট্যাটাস</th>
                    <th style="padding: 1rem 0; text-align: right;">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($recent_tuitions as $t): ?>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 1rem 0;"><?php echo e($t['title']); ?></td>
                        <td style="padding: 1rem 0;"><?php echo e($t['subjects']); ?></td>
                        <td style="padding: 1rem 0;">
                            <span class="btn" style="padding: 0.2rem 0.6rem; font-size: 0.75rem; background-color: <?php echo $t['status'] == 'OPEN' ? '#d1fae5' : '#fee2e2'; ?>; color: <?php echo $t['status'] == 'OPEN' ? '#065f46' : '#991b1b'; ?>;">
                                <?php echo $t['status'] == 'OPEN' ? 'সচল' : 'বন্ধ'; ?>
                            </span>
                        </td>
                        <td style="padding: 1rem 0; text-align: right;">
                            <a href="tuition-details.php?id=<?php echo $t['id']; ?>" style="color: var(--primary-color);"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/layout-footer.php'; ?>
