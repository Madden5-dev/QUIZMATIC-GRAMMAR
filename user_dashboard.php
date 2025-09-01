<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user']['USER_ID'];
$userName = $_SESSION['user']['USER_NAME'];

$recentQuery = $conn->query("
    SELECT q.QUIZ_TITLE, q.QUIZ_DIFFICULTY, q.QUIZ_CATEGORY, uq.DATE_TAKEN
    FROM user_quiz uq
    JOIN quiz q ON uq.QUIZ_ID = q.QUIZ_ID
    WHERE uq.USER_ID = '$userId'
    ORDER BY uq.USERQUIZ_ID DESC  -- Ganti dengan nama kolom ID yang benar
    LIMIT 1
");

$newQuizQuery = $conn->query("
    SELECT QUIZ_ID, QUIZ_TITLE, QUIZ_DIFFICULTY, QUIZ_CATEGORY
    FROM quiz
    ORDER BY QUIZ_ID DESC
    LIMIT 3
");

$progressQuery = $conn->query("
    SELECT q.QUIZ_TITLE, l.LEADERBOARD_SCORE, l.LEADERBOARD_RANK, 
           MAX(uq.DATE_TAKEN) as LAST_TAKEN
    FROM leaderboard l
    JOIN quiz q ON q.QUIZ_ID = l.QUIZ_ID
    JOIN user_quiz uq ON uq.QUIZ_ID = l.QUIZ_ID AND uq.USER_ID = l.USER_ID
    WHERE l.USER_ID = '$userId'
    GROUP BY q.QUIZ_TITLE, l.LEADERBOARD_SCORE, l.LEADERBOARD_RANK
    ORDER BY LAST_TAKEN DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard | Quizmatic Grammar</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="logo_website.png">
  <style>
    :root {
      --primary: #00c3e3;
      --primary-dark: #00a9c7;
      --primary-light: #80e1f1;
      --white: #ffffff;
      --dark: #2b2d42;
      --light: #f8f9fa;
      --border-radius: 12px;
      --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      --transition: all 0.3s ease;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f5f7fa;
      color: var(--dark);
      line-height: 1.6;
    }

    /* Welcome Section */
    .welcome-wrapper {
      display: flex;
      justify-content: center;
      padding: 30px 20px 0;
    }

    .welcome {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 30px;
      width: 100%;
      max-width: 800px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .welcome::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 5px;
      height: 100%;
      background: linear-gradient(to bottom, var(--primary), var(--primary-dark));
    }

    .welcome-top {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 20px;
      margin-bottom: 15px;
    }

    .welcome-top img {
      width: 80px;
      height: auto;
      //border-radius: 50%;
      //object-fit: cover;
      //border: 3px solid var(--primary-light);
    }

    .welcome h2 {
      font-size: 2rem;
      color: var(--dark);
      margin: 0;
    }

    .welcome h2 span {
      color: var(--primary);
      font-weight: 600;
    }

    /* Main Content */
    main {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 25px;
      padding: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }

    section {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 25px;
      transition: var(--transition);
      height: 280px;
      display: flex;
      flex-direction: column;
    }

    section:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    h3 {
      font-size: 1.4rem;
      color: var(--primary);
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid var(--primary-light);
    }

    /* Scrollable Content Areas */
    .scroll-container {
      flex: 1;
      overflow-y: auto;
      padding-right: 10px;
    }

    /* Progress Section */
    .progress-item {
      background: var(--light);
      border-radius: var(--border-radius);
      padding: 18px;
      margin-bottom: 15px;
      transition: var(--transition);
      border-left: 4px solid var(--primary);
    }

    .progress-item:hover {
      background: #e6f7ff;
      transform: translateX(5px);
    }

    .progress-item strong {
      font-size: 1.1rem;
      color: var(--dark);
      display: block;
      margin-bottom: 5px;
    }

    .progress-stats {
      display: flex;
      gap: 15px;
      font-size: 0.9rem;
      color: #555;
    }

    /* Recent Activity Section */
    .activity-item {
      background: var(--light);
      border-radius: var(--border-radius);
      padding: 18px;
      margin-bottom: 15px;
      transition: var(--transition);
    }

    .activity-item:hover {
      background: #e6f7ff;
      transform: translateX(5px);
    }

    .activity-item strong {
      font-size: 1.1rem;
      color: var(--dark);
      display: block;
      margin-bottom: 5px;
    }

    .activity-details {
      display: flex;
        flex-direction: column;
        gap: 5px;
        font-size: 0.9rem;
        color: #555;
        margin-bottom: 5px;
    }

    .activity-date {
      font-size: 0.85rem;
      color: #777;
      font-style: italic;
    }

    /* New Quizzes Section */
    .quiz-item {
      background: var(--light);
      border-radius: var(--border-radius);
      padding: 18px;
      margin-bottom: 15px;
      transition: var(--transition);
    }

    .quiz-item:hover {
      background: #e6f7ff;
      transform: translateX(5px);
    }

    .quiz-item strong {
      font-size: 1.1rem;
      color: var(--dark);
      display: block;
      margin-bottom: 5px;
    }

    .quiz-details {
      display: flex;
        flex-direction: column;
        gap: 5px;
        font-size: 0.9rem;
        color: #555;
        margin-bottom: 5px;
    }

    .answer-btn {
      background: var(--primary);
      color: var(--white);
      border: none;
      padding: 10px 20px;
      border-radius: 50px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      display: inline-block;
      text-align: center;
      width: 100%;
    }

    .answer-btn:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(0, 195, 227, 0.3);
    }

    /* Scrollbar Styling */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
      background: var(--primary-light);
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--primary);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      main {
        grid-template-columns: 1fr;
        padding: 20px;
      }
      
      section {
        height: auto;
        min-height: 400px;
      }
      
      .welcome-top {
        flex-direction: column;
        text-align: center;
      }
      
      .welcome h2 {
        font-size: 1.6rem;
      }
    }
	.detail-row {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    
    .detail-label {
        font-weight: 600;
        color: var(--primary-dark);
        min-width: 80px;
    }
    
    .detail-value {
        flex: 1;
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="welcome-wrapper">
  <div class="welcome">
    <div class="welcome-top">
      <img src="grad.png" alt="Profile picture">
      <h2>Welcome,<br><span><?= htmlspecialchars($userName) ?></span></h2>
    </div>
  </div>
</div>

<main>
  <section class="user-progress">
    <h3>User Progress</h3>
    <div class="scroll-container">
      <?php if ($progressQuery->num_rows > 0): ?>
        <?php while ($row = $progressQuery->fetch_assoc()): ?>
          <div class="progress-item">
            <strong><?= htmlspecialchars($row['QUIZ_TITLE']) ?></strong>
            <div class="progress-stats">
              <span>Rank: <?= $row['LEADERBOARD_RANK'] ?></span>
              <span>Score: <?= $row['LEADERBOARD_SCORE'] ?></span>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No progress found.</p>
      <?php endif; ?>
    </div>
  </section>

 <section class="recent-activity">
    <h3>Recent Activity</h3>
    <div class="scroll-container">
        <?php if ($recentQuery->num_rows > 0): ?>
            <?php $row = $recentQuery->fetch_assoc(); ?>
            <div class="activity-item">
                <strong><?= htmlspecialchars($row['QUIZ_TITLE']) ?></strong>
                <div class="activity-details">
                    <div class="detail-row">
                        <span class="detail-label">Difficulty:</span>
                        <span class="detail-value"><?= htmlspecialchars($row['QUIZ_DIFFICULTY']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Category:</span>
                        <span class="detail-value"><?= htmlspecialchars($row['QUIZ_CATEGORY']) ?></span>
                    </div>
                </div>
                <div class="activity-date">
                    <?= date('d / m / Y', strtotime($row['DATE_TAKEN'])) ?>
                </div>
            </div>
        <?php else: ?>
            <p>No recent activity</p>
        <?php endif; ?>
    </div>
</section>

<!-- Update the New Quizzes section -->
<section class="new-quizzes">
    <h3>New Quizzes</h3>
    <div class="scroll-container">
        <?php while ($quiz = $newQuizQuery->fetch_assoc()): ?>
            <div class="quiz-item">
                <strong><?= htmlspecialchars($quiz['QUIZ_TITLE']) ?></strong>
                <div class="quiz-details">
                    <div class="detail-row">
                        <span class="detail-label">Difficulty:</span>
                        <span class="detail-value"><?= htmlspecialchars($quiz['QUIZ_DIFFICULTY']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Category:</span>
                        <span class="detail-value"><?= htmlspecialchars($quiz['QUIZ_CATEGORY']) ?></span>
                    </div>
                </div>
                <form method="GET" action="answer_quiz.php">
                    <input type="hidden" name="quiz_id" value="<?= $quiz['QUIZ_ID'] ?>">
                    <button type="submit" class="answer-btn">Answer</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</section>
</main>

<?php include 'logout_modal.php'; ?>
<?php include 'footer.php'; ?>

</body>
</html>