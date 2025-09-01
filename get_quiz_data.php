<?php
header('Content-Type: application/json');
session_start();

$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");

if (isset($_GET['quiz_id'])) {
    $quizId = (int)$_GET['quiz_id'];
    $result = $conn->query("SELECT * FROM QUIZ WHERE QUIZ_ID = $quizId");
    
    if ($result && $result->num_rows > 0) {
        $quiz = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'quiz_id' => $quiz['QUIZ_ID'],
            'quiz_title' => $quiz['QUIZ_TITLE'],
            'quiz_category' => $quiz['QUIZ_CATEGORY'],
            'quiz_difficulty' => $quiz['QUIZ_DIFFICULTY']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Quiz not found'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No quiz ID provided'
    ]);
}
?>