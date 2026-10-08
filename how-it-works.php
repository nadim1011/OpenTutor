<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'কীভাবে কাজ করে';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 3rem 0;">
    <div class="card">
        <h1 class="mb-4">ওপেনটিউটর কীভাবে কাজ করে?</h1>
        <div style="display: grid; gap: 1.5rem;">
            <div class="card" style="background: #f8fafc;">
                <h3>১. প্রয়োজন জানান</h3>
                <p>শিক্ষার্থী/অভিভাবকরা টিউশন পোস্ট তৈরি করেন। তারা শ্রেণি, বিষয়, এলাকা, বেতন ও সময়সহ বিস্তারিত লিখে দেন।</p>
            </div>
            <div class="card" style="background: #f8fafc;">
                <h3>২. শিক্ষক খুঁজুন</h3>
                <p>শিক্ষকের প্রোফাইল, যোগ্যতা, অবস্থান, বেতন ও রেটিং দেখে আপনি সবচেয়ে উপযুক্ত শিক্ষক নির্বাচন করতে পারবেন।</p>
            </div>
            <div class="card" style="background: #f8fafc;">
                <h3>৩. আবেদন দেখুন</h3>
                <p>শিক্ষকরা আপনার পোস্টে আবেদন করবে। প্রতিটি আবেদন যাচাই করে আপনি গ্রহণ বা বাতিল করতে পারবেন।</p>
            </div>
            <div class="card" style="background: #f8fafc;">
                <h3>৪. উপযুক্ত শিক্ষক নির্বাচন করুন</h3>
                <p>গৃহীত আবেদনের ভিত্তিতে আপনি সেরা শিক্ষককে বেছে নিয়ে টিউশন শুরু করতে পারবেন।</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
