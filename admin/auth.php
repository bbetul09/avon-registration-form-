<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (($_SESSION['admin_logged_in'] ?? false) !== true) {
    header('Location: login.php');
    exit;
}
