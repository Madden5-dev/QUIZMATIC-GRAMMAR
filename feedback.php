<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$userId = $_SESSION['user']['USER_ID'] ?? 0;
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $userId) {
    $message = $conn->real_escape_string($_POST['message']);
    $conn->query("INSERT INTO feedback (FEEDBACK_TEXT, USER_ID) VALUES ('$message', '$userId')");
    $success = true;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Feedback</title>
  <link rel="icon" href="logo_website.png" type="image/png">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f2f6ff;
      margin: 0;
      padding: 0;
    }

    .feedback-container {
      max-width: 600px;
      margin: 60px auto;
      background-color: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }

    .feedback-container h2 {
      text-align: center;
      color: #0066d6;
      margin-bottom: 25px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 8px;
    }

    .form-group textarea {
      width: 95.5%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      resize: vertical;
      min-height: 100px;
    }

    .submit-btn {
      background-color: #0066d6;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      width: 100%;
    }

    .submit-btn:hover {
      background-color: #0051a8;
    }

    .success-message {
      background-color: #d4edda;
      border: 1px solid #c3e6cb;
      padding: 15px;
      border-radius: 8px;
      color: #155724;
      margin-bottom: 20px;
      text-align: center;
    }

    .home-btn {
	margin-top: 20px;
  background-color: white;
  color: #0066d6;
  border: 2px solid #0066d6;
  padding: 10px 20px;
  border-radius: 25px;
  cursor: pointer;
  font-weight: bold;
  font-size: 14px;
  transition: 0.3s;
}

.home-btn:hover {
  background-color: #0066d6;
  color: white;
}


    .not-logged {
      text-align: center;
      font-style: italic;
      color: #666;
    }
  </style>
</head>
<body>

<div class="feedback-container">
  <h2>📝 Feedback Form</h2>

  <?php if (!$userId): ?>
    <p class="not-logged">You must be logged in to submit feedback.</p>
  <?php elseif ($success): ?>
    <div class="success-message">
      Thank you for your feedback!
    </div>
  <?php else: ?>
    <form method="POST" action="feedback.php">
      <div class="form-group">
        <label for="message">Your Message</label>
        <textarea name="message" id="message" required placeholder="Write your feedback..."></textarea>
      </div>
      <button type="submit" class="submit-btn">Send Feedback</button>
    </form>
  <?php endif; ?>

  <a href="homepage.php" class="back-link">
  <button class="home-btn">← Back to Home</button>
</a>
</div>

</body>
</html>
