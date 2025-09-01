<?php
session_start();

if (isset($_SESSION['user'])) {
    if ($_SESSION['user_type'] === 'administrator') {
        header("Location: quiz_homepage.php");
        exit();
    } else {
        header("Location: quizzes.php");
        exit();
    }
} else {
    header("Location: quizzes.php"); 
    exit();
}
?>
