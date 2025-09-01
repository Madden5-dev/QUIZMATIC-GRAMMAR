<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$quizId = $_GET['quiz_id'] ?? die("No quiz ID provided");
$userId = $_SESSION['user']['USER_ID'] ?? 0;

$conn->query("SET @rank := 0");
$ranked = $conn->query("SELECT * FROM leaderboard WHERE QUIZ_ID='$quizId' ORDER BY LEADERBOARD_SCORE DESC");

$prevRanks = [];
while ($row = $ranked->fetch_assoc()) {
    $quizIdVal = $row['QUIZ_ID'];
    $prevRanks[$quizIdVal] = ($prevRanks[$quizIdVal] ?? 0) + 1;
    $rank = $prevRanks[$quizIdVal];
    $currId = $row['LEADERBOARD_ID'];

    $conn->query("UPDATE leaderboard SET LEADERBOARD_RANK='$rank' WHERE LEADERBOARD_ID='$currId'");
}

$leaderboard = $conn->query("SELECT l.LEADERBOARD_RANK, u.USER_NAME, l.LEADERBOARD_SCORE, 
         (SELECT MAX(DATE_TAKEN) FROM user_quiz uq WHERE uq.USER_ID = u.USER_ID AND uq.QUIZ_ID = l.QUIZ_ID) AS LAST_TAKEN
  FROM leaderboard l 
  JOIN user u ON l.USER_ID = u.USER_ID
  WHERE l.QUIZ_ID = '$quizId'
  ORDER BY l.LEADERBOARD_RANK ASC");

$quiz = $conn->query("SELECT * FROM quiz WHERE QUIZ_ID = '$quizId'")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Leaderboard</title>
  <style>
:root {
  --primary: #00c3e3;
  --primary-dark: #00a9c7;
  --primary-light: #80e1f1;
  --success: #4cc9f0;
  --danger: #ef233c;
  --warning: #f8961e;
  --dark: #2b2d42;
  --light: #f8f9fa;
  --white: #ffffff;
  --border-radius: 12px;
  --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  --transition: all 0.3s ease;
}

body {
  font-family: 'Poppins', sans-serif;
  background-color: #f5f7fa;
  color: var(--dark);
  line-height: 1.6;
  margin: 0;
  padding: 20px;
}

.quiz-container {
  max-width: 800px;
  margin: 30px auto;
  padding: 30px;
  background: var(--white);
  border-radius: var(--border-radius);
  box-shadow: var(--shadow);
  position: relative;
}

.quiz-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 5px;
  height: 100%;
  background: var(--primary);
}

.back-btn {
    background-color: var(--primary);
  color: var(--white);
  border: none;
  padding: 10px 20px;
  border-radius: var(--border-radius);
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 25px;
}

.back-btn:hover {
  background-color: var(--primary-dark);
  transform: translateY(-2px);
}

.leaderboard-box {
  background: var(--white);
  border-radius: var(--border-radius);
  padding: 25px;
  margin: 30px auto;
  box-shadow: var(--shadow);
  border: 1px solid #e0e0e0;
}

.leaderboard-box h2 {
  color: var(--dark);
  text-align: center;
  margin-bottom: 20px;
  font-size: 1.5rem;
  position: relative;
}

.leaderboard-box h2::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 50%;
  transform: translateX(-50%);
  width: 100px;
  height: 3px;
  background: var(--primary);
}

.quiz-info {
  text-align: center;
  margin-bottom: 20px;
  padding: 15px;
  background-color: #f8f9fa;
  border-radius: var(--border-radius);
}

.quiz-info p {
  margin: 0;
  color: var(--dark);
  font-size: 0.95rem;
}

.quiz-info strong {
  color: var(--primary);
}

.rank-item {
  background-color: var(--white);
  border-radius: var(--border-radius);
  padding: 12px 20px;
  margin: 10px 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: var(--transition);
  box-shadow: var(--shadow);
}

.rank-item:hover {
  transform: translateX(5px);
}

.rank-item div:first-child {
  font-weight: 600;
}

.rank-item div:last-child {
  color: var(--dark);
  font-weight: 500;
}

.flame {
  color: #ff6b35;
  margin: 0 5px;
}

.print-btn {
  background-color: var(--success);
  color: var(--white);
  border: none;
  padding: 12px 25px;
  border-radius: var(--border-radius);
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  display: block;
  margin: 30px auto;
  text-align: center;
  width: 200px;
}

.print-btn:hover {
  background-color: #3bb4d8;
  transform: translateY(-2px);
}

@media print {
  body * {
    visibility: hidden;
  }
  .leaderboard-box, .leaderboard-box * {
    visibility: visible;
  }
  .leaderboard-box {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    box-shadow: none;
    border: none;
  }
  .back-btn, .print-btn {
    display: none;
  }
}

@media (max-width: 768px) {
  .quiz-container {
    width: 95%;
    padding: 20px;
  }
}
  </style>
  <link rel="icon" type="image/png" href="logo_website.png">
</head>
<body>
  <div class="quiz-container">
    <button onclick="window.location.href='admin_homepage.php'" class="back-btn">Back to Homepage</button>

    <div class="leaderboard-box">
      <h2 style="text-align:center;">Leaderboard</h2>
      <div class="quiz-info">
        <p>
          <strong>Title:</strong> <?= htmlspecialchars($quiz['QUIZ_TITLE']) ?> |
          <strong>Difficulty:</strong> <?= htmlspecialchars($quiz['QUIZ_DIFFICULTY']) ?> |
          <strong>Category:</strong> <?= htmlspecialchars($quiz['QUIZ_CATEGORY']) ?>
        </p>
      </div>
      <?php while ($row = $leaderboard->fetch_assoc()): ?>
        <div class="rank-item">
          <div><?= $row['LEADERBOARD_RANK'] ?>. <?= htmlspecialchars($row['USER_NAME']) ?></div>
          <div>
            <?= $row['LEADERBOARD_SCORE'] ?> <span class="flame">🔥</span>
            (<?= date("j M Y", strtotime($row['LAST_TAKEN'])) ?>)
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <button class="print-btn" onclick="window.print()">Print Leaderboard</button>

    <?php if (!$userId): ?>
      <p style="text-align:center; margin-top:30px; font-style:italic; color:#555;">
        Login to save your score and comment on this quiz.
      </p>
    <?php endif; ?>
  </div>
</body>
</html>