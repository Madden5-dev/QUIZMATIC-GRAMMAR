<?php
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
$title = $_GET['title'] ?? '';
$response = ['found' => false];

// The search button in edit_quiz.php
// Connects with json
// Grabs title form edit_SearchApi_quiz.php
// Once quiz is found, send the updated quiz to json
$stmt = $conn->prepare("SELECT * FROM QUIZ WHERE QUIZ_TITLE = ?");
$stmt->bind_param("s", $title);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $response['found'] = true;
    $response['quiz'] = $row;
}

header('Content-Type: application/json');
echo json_encode($response);
