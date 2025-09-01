<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");

// Sends json some data
// For creating new title for a quiz
$data = json_decode(file_get_contents("php://input"), true);
$quiz_id = $data['quiz_id'] ?? null;
$new_title = $data['new_title'] ?? '';

if (!$quiz_id || !$new_title) {
    echo json_encode(["success" => false, "message" => "Missing quiz ID or new title."]);
    exit();
}

$stmt = $conn->prepare("UPDATE QUIZ SET QUIZ_TITLE = ? WHERE QUIZ_ID = ?");
$stmt->bind_param("si", $new_title, $quiz_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Quiz title updated successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update title."]);
}
?>
