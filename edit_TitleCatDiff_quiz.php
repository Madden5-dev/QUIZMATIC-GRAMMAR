<?php
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
// JSON here is to connect with edit_quiz.js
// It is a temporary array so admin can edit
// This is for when admin search a quiz and to edit it's title, category and difficulty
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

$title = $_POST['quiz_title'] ?? '';
$title = trim($title);

if (empty($title)) {
    echo json_encode(['success' => false, 'message' => 'Quiz title is required.']);
    exit();
}

$stmt = $conn->prepare("SELECT QUIZ_ID, QUIZ_CATEGORY, QUIZ_DIFFICULTY, QUIZ_TITLE FROM QUIZ WHERE QUIZ_TITLE = ?");
$stmt->bind_param("s", $title);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'success' => true,
        'quiz_id' => $row['QUIZ_ID'],
        'category' => $row['QUIZ_CATEGORY'],
        'difficulty' => $row['QUIZ_DIFFICULTY'],
        'title' => $row['QUIZ_TITLE']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Quiz not found. Please check the title.']);
}
