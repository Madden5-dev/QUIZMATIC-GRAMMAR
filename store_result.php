<?php
session_start();
$data = json_decode(file_get_contents("php://input"), true);

$_SESSION['quiz_result'] = [
  'score' => $data['score'] ?? 0,
  'quiz_id' => $data['quiz_id'] ?? 0,
  'results' => $data['results'] ?? []
];
?>
