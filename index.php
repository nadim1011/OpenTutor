<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'হোম';
require_once __DIR__ . '/includes/header.php';

$subjectList = ['গণিত', 'পদার্থবিজ্ঞান', 'রসায়ন', 'জীববিজ্ঞান', 'ইংরেজি', 'বাংলা', 'আইসিটি'];
?>

<section class="hero" style="background: linear-gradient(rgba(30, 58, 138, 0.9), rgba(30, 58, 138, 0.9)), url('assets/images/hero-bg.jpg'); background-size: cover; padding: 100px 0; color: white; text-align: center;">
    <div class="container">
        <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">সঠিক শিক্ষক, সঠিক শিক্ষার শুরু এখান থেকেই।</h1>
        <p style="font-size: 1.2rem; margin-bottom: 2.5rem; opacity: 0.9;">আপনার প্রয়োজন অনুযায়ী সেরা শিক্ষক খুঁজুন অথবা নিজের টিউশন ক্যারিয়ার শুরু করুন।</p>

        <div class="cta-btns" style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="teachers.php" class="btn btn-secondary" style="padding: 1rem 2rem; font-size: 1.1rem;">শিক্ষক খুঁজুন</a>
            <a href="tuitions.php" class="btn btn-outline" style="padding: 1rem 2rem; font-size: 1.1rem; border-color: white; color: white;">টিউশন খুঁজুন</a>
        </div>

        <div class="card" style="max-width: 800px; margin: 3rem auto 0; padding: 1rem;">
            <form action="teachers.php" method="GET" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 0.5rem;">
                <input type="text" name="subject" class="form-control" placeholder="বিষয় (যেমন: গণিত)">
                <input type="text" name="class" class="form-control" placeholder="শ্রেণি (যেমন: নবম)">
                <input type="text" name="location" class="form-control" placeholder="এলাকা (যেমন: মিরপুর)">
                <button type="submit" class="btn btn-primary">খুঁজুন</button>
            </form>
        </div>
    </div>
</section>

<section style="padding: 5rem 0;">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 3rem;">জনপ্রিয় বিষয়সমূহ</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1.5rem;">
            <?php foreach ($subjectList as $sub): ?>
                <a href="teachers.php?subject=<?php echo urlencode($sub); ?>" class="card" style="text-align: center; padding: 1.5rem; transition: var(--transition);">
                    <i class="fas fa-book" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h4 style="color: var(--text-color);"><?php echo e($sub); ?></h4>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section style="background-color: #f1f5f9; padding: 5rem 0;">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 3rem;">ওপেনটিউটর কীভাবে কাজ করে?</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div class="card" style="text-align: center;">
                <div style="width: 50px; height: 50px; background: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-weight: bold; font-size: 1.2rem;">১</div>
                <h3>প্রয়োজন জানান</h3>
                <p style="color: var(--text-muted); margin-top: 1rem;">আপনার কোন বিষয়ে শিক্ষক প্রয়োজন তা আমাদের জানান এবং টিউশন পোস্ট করুন।</p>
            </div>
            <div class="card" style="text-align: center;">
                <div style="width: 50px; height: 50px; background: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-weight: bold; font-size: 1.2rem;">২</div>
                <h3>শিক্ষক খুঁজুন</h3>
                <p style="color: var(--text-muted); margin-top: 1rem;">আমাদের হাজারো যাচাইকৃত শিক্ষকদের প্রোফাইল দেখে আপনার পছন্দের শিক্ষক নির্বাচন করুন।</p>
            </div>
            <div class="card" style="text-align: center;">
                <div style="width: 50px; height: 50px; background: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-weight: bold; font-size: 1.2rem;">৩</div>
                <h3>আবেদন দেখুন</h3>
                <p style="color: var(--text-muted); margin-top: 1rem;">শিক্ষকরা আপনার পোস্টে আবেদন করবেন। তাদের যোগ্যতা এবং অভিজ্ঞতা যাচাই করুন।</p>
            </div>
            <div class="card" style="text-align: center;">
                <div style="width: 50px; height: 50px; background: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-weight: bold; font-size: 1.2rem;">৪</div>
                <h3>শিক্ষক নির্বাচন করুন</h3>
                <p style="color: var(--text-muted); margin-top: 1rem;">উপযুক্ত শিক্ষকের সাথে কথা বলে আপনার টিউশন শুরু করুন।</p>
            </div>
        </div>
    </div>
</section>

<section style="padding: 5rem 0; background-color: var(--primary-color); color: white; text-align: center;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 3rem;">
            <div>
                <h2 style="font-size: 2.5rem;">৫,০০০+</h2>
                <p style="font-size: 1.1rem; opacity: 0.8;">রেজিস্ট্রার্ড শিক্ষক</p>
            </div>
            <div>
                <h2 style="font-size: 2.5rem;">২,০০০+</h2>
                <p style="font-size: 1.1rem; opacity: 0.8;">টিউশন পোস্ট</p>
            </div>
            <div>
                <h2 style="font-size: 2.5rem;">৯৮%</h2>
                <p style="font-size: 1.1rem; opacity: 0.8;">সফল ম্যাচিং</p>
            </div>
            <div>
                <h2 style="font-size: 2.5rem;">৪.৮/৫</h2>
                <p style="font-size: 1.1rem; opacity: 0.8;">গড় রেটিং</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
