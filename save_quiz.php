<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Specifically for creating a quiz, but this is just the code, the actual web is on create_quiz.php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create_quiz'])) {
    $title = $_POST['quiz_title'];
    $category = $_POST['quiz_category'];
    $difficulty = $_POST['quiz_difficulty'];
    $date = date('Y-m-d'); // includes time

    $stmt = $conn->prepare("INSERT INTO QUIZ (QUIZ_TITLE, QUIZ_CATEGORY, QUIZ_DIFFICULTY, QUIZ_DATE) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $category, $difficulty, $date);
    
    if ($stmt->execute()) {
        $quiz_id = $conn->insert_id;
        $_SESSION['quiz_id'] = $quiz_id;
        $_SESSION['current_question'] = 1;
        $_SESSION['questions'] = [];

        header("Location: create_question.php");
        exit();
    } else {
        echo "Error saving quiz: " . $conn->error;
    }
}
?>
