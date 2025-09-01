<?php
session_start();

if (isset($_SESSION['user'])) {
    if ($_SESSION['user_type'] === 'administrator') {
        header("Location: admin_homepage.php");
        exit();
    } else {
        header("Location: user_dashboard.php");
        exit();
    }
} else {
    header("Location: homepage.php"); 
    exit();
}
?>
