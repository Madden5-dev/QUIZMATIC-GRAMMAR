<?php
session_start();
// Periksa jika user adalah admin


$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Dapatkan semua feedback tanpa tarikh
$feedbackQuery = $conn->query("
    SELECT f.FEEDBACK_ID, f.FEEDBACK_TEXT, u.USER_NAME 
    FROM feedback f
    JOIN user u ON f.USER_ID = u.USER_ID
    ORDER BY f.FEEDBACK_ID DESC
");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Feedback | Quizmatic Grammar</title>
  <link rel="icon" href="logo_website.png" type="image/png">
  <style>
    :root {
      --primary: #00c3e3;
      --primary-dark: #00a9c7;
      --primary-light: #80e1f1;
      --white: #ffffff;
      --light: #f5fbfd;
      --dark: #2b2d42;
      --border-radius: 8px;
      --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f0f9fc;
      color: var(--dark);
      line-height: 1.6;
    }


    .admin-container {
      max-width: 1200px;
	
      margin: 0 auto;
      padding: 0 20px;
    }


    .feedback-container {
	margin-top: 30px;
      background-color: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 30px;
      margin-bottom: 30px;
    }

    .section-title {
      color: var(--primary);
      margin-bottom: 20px;
      font-size: 1.5rem;
      border-bottom: 2px solid var(--primary-light);
      padding-bottom: 10px;
    }

    .feedback-list {
      display: grid;
      gap: 20px;
    }

    .feedback-item {
      background-color: var(--light);
      border-left: 4px solid var(--primary);
      border-radius: var(--border-radius);
      padding: 20px;
      transition: all 0.3s ease;
    }

    .feedback-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0, 195, 227, 0.2);
    }

    .feedback-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      align-items: center;
    }

    .user-name {
      font-weight: 600;
      color: var(--primary-dark);
      font-size: 1.1rem;
    }

    .feedback-text {
      color: var(--dark);
      line-height: 1.6;
    }

    .no-feedback {
      text-align: center;
      padding: 30px;
      color: #666;
      font-style: italic;
    }


    @media (max-width: 768px) {
      .header-content {
        flex-direction: column;
        gap: 15px;
      }
    }

.back-button-container {
  text-align: left;
  margin: 10px auto;
}

.back-button {
  display: inline-block;
  padding: 12px 24px;
  background-color: var(--primary);
  color: white;
  border-radius: var(--border-radius);
  text-decoration: none;
  box-shadow: var(--shadow);
  font-weight: 500;
  transition: all 0.3s ease;
}

.back-button:hover {
  background-color: var(--primary-dark);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 195, 227, 0.3);
}
  </style>
</head>
<body>

  <?php include 'header.php'; ?>

  <main class="admin-container">

    <section class="feedback-container">
	<div class="back-button-container">
    <a href="admin_homepage.php" class="back-button">
      ← Back
    </a>
  </div>

      <h2 class="section-title">All User Feedback</h2>
      
      <div class="feedback-list">
        <?php if ($feedbackQuery->num_rows > 0): ?>
          <?php while ($feedback = $feedbackQuery->fetch_assoc()): ?>
            <div class="feedback-item">
              <div class="feedback-header">
                <span class="user-name">👤 <?= htmlspecialchars($feedback['USER_NAME']) ?></span>
              </div>
              <p class="feedback-text"><?= htmlspecialchars($feedback['FEEDBACK_TEXT']) ?></p>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="no-feedback">
            <p>No feedback has been submitted yet.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>
</body>
</html>