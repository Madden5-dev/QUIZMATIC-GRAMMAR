<?php
session_start();
// Editing question use the same code with create_question.php
// but by using the editing = true, the system knows when the user edit or create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quiz_id']) && !empty($_POST['quiz_id'])) {
    $_SESSION['quiz_id'] = (int)$_POST['quiz_id'];  // safely cast to integer
    $_SESSION['editing'] = true;                    // enable editing mode
    $_SESSION['current_question'] = 1;
    unset($_SESSION['questions']);                  // force reload from DB in create_question.php

    header("Location: create_question.php");
    exit();
} else {
    // fallback if no quiz_id provided
    $_SESSION['feedback'] = "Quiz ID missing. Cannot edit.";
    header("Location: edit_quiz.php");
    exit();
}
