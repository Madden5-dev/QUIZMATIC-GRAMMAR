<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$quizData = $_SESSION['quiz_result'] ?? null;
if (!$quizData) die("No quiz result found.");

$score = $quizData['score'];
$quizId = $quizData['quiz_id'];
$results = $quizData['results'];
$userId = $_SESSION['user']['USER_ID'] ?? 0;

if ($userId && $quizId) {
    // Semak sama ada pengguna sudah ada dalam leaderboard
    $check = $conn->query("SELECT * FROM leaderboard WHERE USER_ID='$userId' AND QUIZ_ID='$quizId'");
    if ($check->num_rows > 0) {
        // Update dengan markah terkini tanpa kira markah lama lebih tinggi atau tidak
        $row = $check->fetch_assoc();
        $conn->query("UPDATE leaderboard SET LEADERBOARD_SCORE='$score' WHERE LEADERBOARD_ID='{$row['LEADERBOARD_ID']}'");
    } else {
        // Jika tiada rekod, masukkan sebagai rekod baru
        $conn->query("INSERT INTO leaderboard (USER_ID, QUIZ_ID, LEADERBOARD_SCORE) VALUES ('$userId', '$quizId', '$score')");
    }

    // Rekod sejarah pengguna jawab kuiz
    $conn->query("INSERT INTO user_quiz (USER_ID, QUIZ_ID, DATE_TAKEN) VALUES ('$userId', '$quizId', NOW())");

    // Kira semula ranking
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
}

// Modified query to remove LAST_TAKEN date
$leaderboard = $conn->query("SELECT l.LEADERBOARD_RANK, u.USER_NAME, l.LEADERBOARD_SCORE
  FROM leaderboard l 
  JOIN user u ON l.USER_ID = u.USER_ID
  WHERE l.QUIZ_ID = '$quizId'
  ORDER BY l.LEADERBOARD_RANK ASC");

$quiz = $conn->query("SELECT * FROM quiz WHERE QUIZ_ID = '$quizId'")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Result</title>
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

.result-wrapper {
  background: linear-gradient(135deg, #f8f9fa, #e9f7fe);
  border-radius: var(--border-radius);
  padding: 25px;
  margin-bottom: 30px;
  box-shadow: var(--shadow);
  border: 1px solid #e0e0e0;
  text-align: center;
}

.result-wrapper h3 {
  font-size: 1.5rem;
  color: var(--dark);
  margin-bottom: 20px;
  position: relative;
  display: inline-block;
}

.result-wrapper h3::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 3px;
  background: var(--primary);
}

.result-columns {
  display: flex;
  justify-content: space-between;
  margin: 25px 0;
  flex-wrap: wrap;
  gap: 20px;
}

.result-column {
  flex: 1;
  min-width: 200px;
}

.result-column ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.result-column li {
  padding: 10px 15px;
  margin: 8px 0;
  background-color: var(--white);
  border-radius: var(--border-radius);
  box-shadow: var(--shadow);
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: var(--transition);
}

.result-column li:hover {
  transform: translateX(5px);
}

.result-icon {
  font-weight: bold;
  font-size: 1.1rem;
  margin-left: 10px;
}

.result-icon.correct {
  color: #28a745;
}

.result-icon.wrong {
  color: var(--danger);
}

.score-button {
  background-color: var(--primary);
  color: var(--white);
  padding: 12px 30px;
  border: none;
  border-radius: 50px;
  font-weight: 700;
  font-size: 1.1rem;
  cursor: default;
  margin-top: 15px;
  box-shadow: 0 4px 15px rgba(0, 195, 227, 0.3);
  transition: var(--transition);
}

.score-button:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(0, 195, 227, 0.4);
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

.comment-btn {
  background-color: var(--primary);
  color: var(--white);
  padding: 12px 25px;
  border: none;
  border-radius: 50px;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  display: inline-flex;
  align-items: center;
  gap: 8px;
  margin-top: 20px;
  box-shadow: 0 4px 15px rgba(0, 195, 227, 0.3);
}

.comment-btn:hover {
  background-color: var(--primary-dark);
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(0, 195, 227, 0.4);
}

@media (max-width: 768px) {
  .quiz-container {
    width: 95%;
    padding: 20px;
  }
  
  .result-columns {
    flex-direction: column;
    gap: 15px;
  }
  
  .result-column {
    width: 100%;
  }
}
  </style>
  <link rel="icon" type="image/png" href="logo_website.png">
</head>
<body>
  <div class="quiz-container">
    <button onclick="window.location.href='quizzes.php'" class="back-btn">Back to Quizzes</button>

    <div class="result-wrapper" style="margin-bottom:30px;">
      <h3>Your Result</h3>
      <div class="result-columns">
        <div class="result-column">
          <ul>
            <?php foreach (array_slice($results, 0, 5) as $r): ?>
              <li>Question <?= $r['no'] ?> 
                <span class="result-icon <?= $r['correct'] ? 'correct' : 'wrong' ?>">
                  <?= $r['correct'] ? '✓' : '✗' ?>
                </span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="result-column">
          <ul>
            <?php foreach (array_slice($results, 5) as $r): ?>
              <li>Question <?= $r['no'] ?> 
                <span class="result-icon <?= $r['correct'] ? 'correct' : 'wrong' ?>">
                  <?= $r['correct'] ? '✓' : '✗' ?>
                </span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <button class="score-button">POINTS: <?= $score ?></button>
    </div>

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
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <?php if ($userId): ?>
      <div style="text-align:center; margin-top:30px;">
        <form method="GET" action="comment.php" style="display:inline;">
          <input type="hidden" name="quiz_id" value="<?= $quizId ?>">
          <button type="submit" class="comment-btn">💬 Go to Comments</button>
        </form>
      </div>
    <?php else: ?>
      <p style="text-align:center; margin-top:30px; font-style:italic; color:#555;">
        Please log in first to save your score and comment. Progress will not be saved otherwise.
      </p>
    <?php endif; ?>
  </div>
</body>
</html>