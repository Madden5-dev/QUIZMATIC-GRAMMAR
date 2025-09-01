<?php
session_start();
$isLoggedIn = isset($_SESSION['user']);

// Connect to database
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch latest 2 quizzes
$newQuizzes = $conn->query("SELECT QUIZ_ID, QUIZ_TITLE, QUIZ_DIFFICULTY, QUIZ_CATEGORY FROM quiz ORDER BY QUIZ_ID DESC LIMIT 2");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quizmatic Grammar - Home</title>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="icon" type="image/png" href="logo_website.png">
	<link rel="stylesheet" href="root-scrollbar.css">
  <!--<link rel="stylesheet" href="homepage.css">-->
	<style>
:root {
  --primary: #00c3e3;
  --primary-dark: #00a9c7;
  --primary-light: #80e1f1;
  --secondary: #7209b7;
  --accent: #f72585;
  --success: #4cc9f0;
  --danger: #ef233c;
  --warning: #f8961e;
  --dark: #2b2d42;
  --light: #f8f9fa;
  --white: #ffffff;
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
  background-color: var(--light);
  color: var(--dark);
  line-height: 1.6;

}


/* Hero Section */
.hero {
  text-align: center;
  padding: 80px 20px;
  background: var(--white); /* Changed from gradient to white */
  color: var(--dark); /* Changed text color to dark for better contrast */
  position: relative;
  overflow: hidden;
}

.hero h1 {
  font-size: 2.8rem;
  margin: 20px 0;
  line-height: 1.2;
  color: var(--primary); /* Added primary color to heading */
}

.hero p {
  font-size: 1.3rem;
  max-width: 700px;
  margin: 0 auto;
  color: #555; /* Darker text for better readability on white */
}
.hero img {
  width: 100%;
  max-height: 400px;
  object-fit: cover;
  border-radius: var(--border-radius);
  box-shadow: var(--shadow);
  margin-bottom: 30px;
}

/* Featured Section */
.featured {
  padding: 60px 20px;
  text-align: center;
  background-color: var(--white);
}

.featured img {
  width: 100%;
  max-height: 400px;
  object-fit: cover;
  border-radius: var(--border-radius);
  box-shadow: var(--shadow);
  margin-bottom: 30px;
}

.featured h3 {
  font-size: 1.2rem;
  font-weight: 500;
  color: var(--primary);
  margin-bottom: 10px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.featured h2 {
  font-size: 2.2rem;
  margin-bottom: 20px;
  color: var(--dark);
}

.featured p {
  font-size: 1.1rem;
  max-width: 700px;
  margin: 0 auto;
  color: #555;
}

/* New Quizzes Section */
.quizzes-section {
  padding: 60px 20px;
  background-color: var(--light);
  text-align: center;
}

.quizzes-section h2 {
  font-size: 2.2rem;
  margin-bottom: 40px;
  color: var(--dark);
}

.quiz-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
}

.quiz-card {
  background: var(--white);
  border-radius: var(--border-radius);
  padding: 30px;
  box-shadow: var(--shadow);
  transition: var(--transition);
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.quiz-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.quiz-card h2 {
  font-size: 1.4rem;
  margin-bottom: 15px;
  color: var(--dark);
}

.difficulty {
  font-weight: 600;
  margin-bottom: 15px;
  color: #555;
}

.difficulty::before {
  content: "•";
  margin: 0 5px;
}

.badge-container {
  margin: 15px 0;
}

.badge {
  display: inline-block;
  padding: 6px 15px;
  border-radius: 50px;
  font-size: 0.8rem;
  font-weight: 600;
  letter-spacing: 0.5px;
}

.badge.type {
  background-color: var(--primary);
  color: var(--white);
}

.btn-quiz {
  margin-top: 20px;
  padding: 12px 30px;
  background-color: var(--primary);
  color: var(--white);
  border: none;
  border-radius: 50px;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  width: 100%;
  max-width: 200px;
}

.btn-quiz:hover {
  background-color: var(--primary-dark);
  transform: translateY(-2px);
}

/* View All Button */
.view-all {
  text-align: center;
  padding: 0 20px 60px;
}

.view-all button {
  background-color: var(--primary);
  color: var(--white);
  font-size: 1rem;
  border: none;
  border-radius: 50px;
  padding: 14px 30px;
  cursor: pointer;
  font-weight: 600;
  transition: var(--transition);
  box-shadow: 0 4px 15px rgba(0, 195, 227, 0.3);
}

.view-all button:hover {
  background-color: var(--primary-dark);
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(0, 195, 227, 0.4);
}

/* Responsive Design */
@media (max-width: 768px) {
  .hero h1 {
    font-size: 2rem;
  }
  
  .hero p,
  .featured p {
    font-size: 1rem;
  }
  
  .featured h2,
  .quizzes-section h2 {
    font-size: 1.8rem;
  }
  
  .quiz-grid {
    grid-template-columns: 1fr;
  }
}
	</style>
</head>
<body>

  <?php include 'header.php'; ?>


  <!-- Upper Section -->
  <!-- Upper Section -->
<section class="hero">
  <img src="graduate.jpeg" alt="Graduation Image" />
  <h1>Improve your English with <br> interactive quizzes</h1>
  <p>
    Create, practice, and master English language skills through fun quizzes.
    Get instant feedback and support from our community.
  </p>
</section>

 <!-- Middle Section -->
  <section class="featured">
    <img src="english.jpeg" alt="English Poster" />
    <h3 style="font-size:25px;">Featured Quizzes</h3>
    <h2>Popular English Exercises</h2>
    <p style="font-size:25px;">Challenge yourself with these community favorites to improve your English skills.</p>
  </section>

  <!-- Lower Section -->

	<section class="quizzes-section">  
  <h2>New Quizzes</h2>
  <div class="quiz-grid">
    <?php while ($quiz = $newQuizzes->fetch_assoc()): ?>
      <div class="quiz-card">
        <h2><?= htmlspecialchars($quiz['QUIZ_TITLE']) ?></h2>
      <div class="difficulty">Difficulty: <?= htmlspecialchars($quiz['QUIZ_DIFFICULTY']) ?></div>
      <div>
	
        <span class="badge type"><?= strtoupper(htmlspecialchars($quiz['QUIZ_CATEGORY'])) ?></span>
      </div>
      <button class="btn-quiz" onclick="window.location.href='answer_quiz.php?quiz_id=<?= $quiz['QUIZ_ID'] ?>'">Take Quiz</button>

      </div>
    <?php endwhile; ?>
  </div>
</section>

  <div class="view-all">
    <button onclick="window.location.href='quizzes.php'">View All Quizzes</button>
  </div>

<?php include 'logout_modal.php'; ?>

<?php include 'footer.php'; ?>


</body>
</html>
