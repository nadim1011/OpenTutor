<?php
// opentutor/includes/functions.php

/**
 * Escapes HTML for output
 */
function e($string) {
    if ($string === null) {
        return '';
    }
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirects to a given URL
 */
function redirect($path) {
    $path = ltrim($path, '/');
    $currentFolder = basename(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')));
    $dashboardFolders = ['student', 'tutor', 'admin'];

    if (strpos($path, '/') === false && in_array($currentFolder, $dashboardFolders, true)) {
        $roleFolder = strtolower($_SESSION['user_role'] ?? $currentFolder);
        if (in_array($roleFolder, $dashboardFolders, true)) {
            $path = $roleFolder . '/' . $path;
        }
    }

    header('Location: ' . BASE_URL . $path);
    exit();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current user data
 */
function currentUser($pdo) {
    if (!isLoggedIn()) return null;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

/**
 * Check CSRF token
 */
function verify_csrf($token = null) {
    if ($token === null) {
      $token = $_POST['csrf_token'] ?? '';
    }
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}

/**
 * Return current CSRF token value.
 */
function csrf_token() {
    return $_SESSION['csrf_token'] ?? '';
}

/**
 * Generate CSRF hidden input
 */
function csrf_input() {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Format currency in BDT
 */
function format_bdt($amount) {
    return '৳' . number_format((float)$amount, 0, '.', ',');
}

/**
 * Get Bangla Role Name
 */
function get_role_bn($role) {
    $roles = [
        'STUDENT' => 'শিক্ষার্থী/অভিভাবক',
        'TUTOR' => 'শিক্ষক/টিউটর',
        'ADMIN' => 'প্রশাসক'
    ];
    return $roles[$role] ?? $role;
}

/**
 * Get status badge class.
 */
function get_status_class($status) {
    $classes = [
        'PENDING' => 'bg-warning',
        'ACTIVE' => 'bg-success',
        'SUSPENDED' => 'bg-danger',
        'OPEN' => 'bg-primary',
        'CLOSED' => 'bg-secondary',
        'ACCEPTED' => 'bg-success',
        'REJECTED' => 'bg-danger',
        'VERIFIED' => 'bg-info',
        'RESOLVED' => 'bg-success',
        'DISMISSED' => 'bg-secondary'
    ];
    return $classes[$status] ?? 'bg-light';
}

function pretty_status($status) {
    $map = [
        'PENDING' => 'অপেক্ষমাণ',
        'ACTIVE' => 'সক্রিয়',
        'SUSPENDED' => 'স্থগিত',
        'OPEN' => 'খোলা',
        'CLOSED' => 'বন্ধ',
        'ACCEPTED' => 'গৃহীত',
        'REJECTED' => 'বাতিল',
        'VERIFIED' => 'যাচাইকৃত',
        'RESOLVED' => 'মীমাংসিত',
        'DISMISSED' => 'খারিজ',
        'COMPLETED' => 'সম্পন্ন',
        'WITHDRAWN' => 'প্রত্যাহার',
        'UNVERIFIED' => 'অযাচাইকৃত',
        'REJECTED' => 'প্রত্যাখ্যাত'
    ];
    return $map[$status] ?? ucfirst(strtolower($status));
}

function highlight_text($value) {
    return !empty($value) ? e($value) : 'তথ্য নেই';
}
?>
