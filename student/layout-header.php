<?php
// opentutor/student/layout-header.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('STUDENT');
require_once __DIR__ . '/../includes/header.php';

$user = currentUser($pdo);
?>
