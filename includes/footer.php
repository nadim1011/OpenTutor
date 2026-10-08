<?php
// opentutor/includes/footer.php
?>
<?php if (!empty($isDashboardPage)): ?>
            </section>
        </div>
<?php endif; ?>
</main>

<footer>
    <div class="container">
        <div class="footer-grid">
            <div>
                <h3 class="logo" style="color: white; margin-bottom: 1rem;"><?php echo APP_NAME_BN; ?></h3>
                <p><?php echo APP_TAGLINE; ?>। সঠিক শিক্ষার শুরু এখান থেকেই।</p>
            </div>
            <div>
                <h4>দ্রুত লিংক</h4>
                <ul style="margin-top: 1rem;">
                    <li><a href="<?php echo BASE_URL; ?>teachers.php">শিক্ষক খুঁজুন</a></li>
                    <li><a href="<?php echo BASE_URL; ?>tuitions.php">টিউশন খুঁজুন</a></li>
                    <li><a href="<?php echo BASE_URL; ?>register.php">নিবন্ধন করুন</a></li>
                </ul>
            </div>
            <div>
                <h4>সহায়তা</h4>
                <ul style="margin-top: 1rem;">
                    <li><a href="<?php echo BASE_URL; ?>how-it-works.php">কীভাবে কাজ করে</a></li>
                    <li><a href="<?php echo BASE_URL; ?>contact.php">যোগাযোগ</a></li>
                    <li><a href="<?php echo BASE_URL; ?>privacy.php">গোপনীয়তা নীতি</a></li>
                </ul>
            </div>
            <div>
                <h4>যোগাযোগ</h4>
                <p style="margin-top: 1rem;">ইমেইল: support@opentutor.com</p>
                <p>ফোন: +৮৮০ ১৭০০০০০০০০</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; ২০২৬ <?php echo APP_NAME_BN; ?> — সর্বস্বত্ব সংরক্ষিত।</p>
        </div>
    </div>
</footer>

<script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
</body>
</html>
