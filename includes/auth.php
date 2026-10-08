<?php
// opentutor/includes/auth.php

function require_login() {
    if (!isLoggedIn()) {
        $_SESSION['error'] = 'অনুগ্রহ করে প্রথমে লগইন করুন।';
        redirect('login.php');
    }
}

function requireLogin() {
    require_login();
}

function require_role($role) {
    require_login();
    if ($_SESSION['user_role'] !== $role) {
        $_SESSION['error'] = 'এই পৃষ্ঠাটি দেখার অনুমতি আপনার নেই।';
        redirect('index.php');
    }
}

function requireRole($role) {
    require_role($role);
}

function require_admin() {
    require_role('ADMIN');
}

function redirect_if_logged_in() {
    if (isLoggedIn()) {
        if ($_SESSION['user_role'] === 'ADMIN') {
            redirect('admin/index.php');
        } elseif ($_SESSION['user_role'] === 'TUTOR') {
            redirect('tutor/index.php');
        } else {
            redirect('student/index.php');
        }
    }
}
?>
