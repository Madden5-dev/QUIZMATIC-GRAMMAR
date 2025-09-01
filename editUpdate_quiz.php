<?php
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");

// For updating category and difficulty
$quiz_id = $_POST['quiz_id'];
$category = $_POST['quiz_category'];
$difficulty = $_POST['quiz_difficulty'];

$stmt = $conn->prepare("UPDATE QUIZ SET QUIZ_CATEGORY=?, QUIZ_DIFFICULTY=? WHERE QUIZ_ID=?");
$stmt->bind_param("ssi", $category, $difficulty, $quiz_id);
$stmt->execute();

echo "<script>alert('Quiz updated successfully!'); window.location='edit_quiz.php';</script>";
exit();
