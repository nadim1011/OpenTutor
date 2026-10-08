<?php
// opentutor/config/config.php

// App Constants
define('APP_NAME', 'OpenTutor');
define('APP_NAME_BN', 'ওপেনটিউটর');
define('APP_TAGLINE', 'সঠিক শিক্ষক, সঠিক শিক্ষা');

$httpProtocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$scriptDir = trim(str_replace('\\', '/', dirname($scriptName)), '/');
if (in_array(basename($scriptDir), ['student', 'tutor', 'admin'], true)) {
    $scriptDir = trim(str_replace('\\', '/', dirname($scriptDir)), '/');
}
if ($scriptDir === '.' || $scriptDir === '/' || $scriptDir === '') {
    $scriptDir = '';
}
define('BASE_URL', $httpProtocol . '://' . $host . ($scriptDir !== '' ? '/' . $scriptDir : '') . '/');

// Session configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Error reporting (Enable during development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
