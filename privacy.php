<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'গোপনীয়তা নীতি';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 3rem 0;">
    <div class="card">
        <h1>গোপনীয়তা নীতি</h1>
        <p>OpenTutor আপনার ব্যক্তিগত তথ্যকে নিরাপদে রাখে। আমরা শুধুমাত্র প্ল্যাটফর্মের সেবা প্রদান, নিরাপত্তা ও ব্যবহারকারীর অভিজ্ঞতা উন্নত করার জন্য তথ্য ব্যবহার করি।</p>
        <p>আপনার তথ্য অন্য কারও সাথে বাণিজ্যিক উদ্দেশ্যে শেয়ার করা হয় না।</p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
