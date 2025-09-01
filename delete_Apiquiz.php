<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quiz_id'])) {
    $quiz_id = (int) $_POST['quiz_id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // First delete questions
        $conn->query("DELETE FROM QUESTION WHERE QUIZ_ID = $quiz_id");

        // Then delete quiz
        $conn->query("DELETE FROM QUIZ WHERE QUIZ_ID = $quiz_id");

        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Deletion failed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
