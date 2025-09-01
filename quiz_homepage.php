<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quizzes Dashboard</title>
  <link rel="stylesheet" href="quiz_homepage.css">
<link rel="icon" type="image/png" href="logo_website.png">
	
</head>
<body>

  <?php include 'header.php'; ?>

  <main>
    <div class="button-container">
      <div class="quiz-button">
        <h2>Create a New Quiz</h2>
        <a href="create_quiz.php" class="button">Go Create</a>
      </div>
      <div class="quiz-button">
        <h2>Edit Quiz</h2>
        <a href="edit_quiz.php" class="button">Go Edit</a>
      </div>
      <div class="quiz-button">
        <h2>Delete Quiz</h2>
        <a href="delete_quiz.php" class="button">Go Delete</a>
      </div>
    </div>
  </main>

<?php include 'logout_modal.php'; ?>

</body>
</html>
