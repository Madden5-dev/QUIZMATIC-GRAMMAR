<!-- A deletion code to delete quizzes -->
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");

if (isset($_SESSION['quiz_id'])) {
    $quiz_id = $_SESSION['quiz_id'];

    // Delete all questions for this quiz first
    $conn->query("DELETE FROM QUESTION WHERE QUIZ_ID = $quiz_id");

    // Then delete the quiz
    $conn->query("DELETE FROM QUIZ WHERE QUIZ_ID = $quiz_id");

    // Clear session
    unset($_SESSION['quiz_id']);
    unset($_SESSION['questions']);
    unset($_SESSION['current_question']);
}

header("Location: create_quiz.php");
exit();
?>
