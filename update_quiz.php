<?php
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
header('Content-Type: application/json');

// Use for updating quiz/quiz, change title,category,difficulty
// This is not for editing question
$quiz_id = $_POST['quiz_id'] ?? '';
$title = $_POST['new_title'] ?? '';
$category = $_POST['quiz_category'] ?? '';
$difficulty = $_POST['quiz_difficulty'] ?? '';

if (!$quiz_id || !$title || !$category || !$difficulty) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

$stmt = $conn->prepare("UPDATE QUIZ SET QUIZ_TITLE = ?, QUIZ_CATEGORY = ?, QUIZ_DIFFICULTY = ? WHERE QUIZ_ID = ?");
$stmt->bind_param("sssi", $title, $category, $difficulty, $quiz_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database update failed.']);
}
?>
