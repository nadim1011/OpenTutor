<?php
// opentutor/logout.php
require_once 'config/config.php';
require_once 'includes/functions.php';

session_unset();
session_destroy();

session_start();
$_SESSION['success'] = "সফলভাবে লগআউট করেছেন।";
header("Location: " . BASE_URL . "login.php");
exit();
?>
