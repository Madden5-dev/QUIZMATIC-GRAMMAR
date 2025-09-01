<?php
// Header here to connect with edit_TitleCatDiff_quiz.php and edit_quiz.php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");

$quiz_title = $_POST['quiz_title'] ?? '';
// Receive quiz_title so admin can edit the questions inside
// It is a temporary array for edit_TitleCatDiff_quiz.php
// It is a temporary array for JSON 
// Then send data to JSON so it can act
if (!$quiz_title) {
    echo json_encode(['success' => false, 'message' => 'No quiz title provided.']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM QUIZ WHERE QUIZ_TITLE = ?");
$stmt->bind_param("s", $quiz_title);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $quiz = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        'quiz_id' => $quiz['QUIZ_ID'],
        'quiz_title' => $quiz['QUIZ_TITLE'],
        'quiz_category' => $quiz['QUIZ_CATEGORY'],
        'quiz_difficulty' => $quiz['QUIZ_DIFFICULTY']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Quiz not found.']);
}
?>
