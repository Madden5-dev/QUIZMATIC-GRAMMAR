<?php
session_start();

$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['user_id'];
$quizId = $data['quiz_id'];
$score = $data['score'];

if ($userId && $quizId) {
    // Simpan markah terkini tanpa banding dengan markah lama
    $stmt = $conn->prepare("
        INSERT INTO leaderboard (USER_ID, QUIZ_ID, LEADERBOARD_SCORE, DATE_TAKEN) 
        VALUES (?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE 
        LEADERBOARD_SCORE = VALUES(LEADERBOARD_SCORE),
        DATE_TAKEN = VALUES(DATE_TAKEN)
    ");
    $stmt->bind_param("iii", $userId, $quizId, $score);
    $stmt->execute();

    // Catat sejarah attempt
    $conn->query("INSERT INTO user_quiz (USER_ID, QUIZ_ID, DATE_TAKEN) VALUES ('$userId', '$quizId', NOW())");

    // Update ranking
    $conn->query("
        UPDATE leaderboard l
        JOIN (
            SELECT USER_ID, 
                   QUIZ_ID,
                   @rank := @rank + 1 AS rank
            FROM leaderboard, 
                 (SELECT @rank := 0) r
            WHERE QUIZ_ID = '$quizId'
            ORDER BY DATE_TAKEN DESC, LEADERBOARD_SCORE DESC
        ) ranks ON l.USER_ID = ranks.USER_ID AND l.QUIZ_ID = ranks.QUIZ_ID
        SET l.LEADERBOARD_RANK = ranks.rank
    ");
}

echo json_encode(['success' => true]);
?>