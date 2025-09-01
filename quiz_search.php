<?php
session_start();
$isLoggedIn = isset($_SESSION['USER_NAME']);

// Database connection
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process filters
$search = $_GET['search'] ?? '';
$difficulty = $_GET['difficulty'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "SELECT * FROM quiz WHERE 1";
if ($search) $sql .= " AND QUIZ_TITLE LIKE '%" . $conn->real_escape_string($search) . "%'";
if ($difficulty) $sql .= " AND QUIZ_DIFFICULTY = '" . $conn->real_escape_string($difficulty) . "'";
if ($category) $sql .= " AND QUIZ_CATEGORY = '" . $conn->real_escape_string($category) . "'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quiz Search</title>
  <style>
    /* Modern CSS Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* Color Variables */
    :root {
      --primary: #00c3e3;
      --primary-dark: #009bb5;
      --secondary: #0066d6;
      --secondary-dark: #0000A5;
      --white: #ffffff;
      --light-gray: #f7f7f7;
      --medium-gray: #e0e0e0;
      --dark-gray: #333333;
      --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      --border-radius: 12px;
      --transition: all 0.3s ease;
    }

    /* Base Styles */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--light-gray);
      color: var(--dark-gray);
      line-height: 1.6;
    }

    /* Header Styles */
    header {
      background-color: var(--white);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 50px;
      box-shadow: var(--shadow);
      position: relative;
    }

    header img.logo {
      width: 100px;
      height: auto;
      transition: var(--transition);
    }

    header img.logo:hover {
      transform: scale(1.05);
    }

    .center-nav {
      position: absolute;
      left: 0;
      right: 0;
      display: flex;
      justify-content: center;
      pointer-events: none;
    }

    nav {
      display: flex;
      gap: 20px;
    }

    nav a {
      margin: 0;
      text-decoration: none;
      color: var(--dark-gray);
      font-weight: 600;
      font-size: 18px;
      pointer-events: auto;
      padding: 8px 12px;
      border-radius: var(--border-radius);
      transition: var(--transition);
    }

    nav a:hover {
      background-color: var(--primary-light);
      color: var(--primary-dark);
    }

    .right-section {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .nav-button {
      background-color: var(--secondary);
      color: var(--white);
      border: none;
      padding: 10px 20px;
      border-radius: var(--border-radius);
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
    }

    .nav-button:hover {
      background-color: var(--secondary-dark);
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    /* Main Container */
    .container {
      max-width: 1200px;
      margin: 40px auto;
      padding: 0 30px;
    }

    /* Page Title */
    h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 30px;
      color: var(--dark-gray);
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    /* Filter Form */
    .filter-form {
      background: var(--white);
      padding: 30px;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      margin-bottom: 30px;
    }

    .filter-form input[type="text"],
    .filter-form select {
      width: 100%;
      padding: 15px 20px;
      font-size: 16px;
      border-radius: var(--border-radius);
      border: 2px solid var(--medium-gray);
      margin-bottom: 20px;
      transition: var(--transition);
    }

    .filter-form input[type="text"]:focus,
    .filter-form select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(0, 195, 227, 0.2);
    }

    /* Button Group */
    .btn-group {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }

    .btn-search,
    .btn-reset {
      flex: 1;
      min-width: 120px;
      padding: 12px 20px;
      border-radius: var(--border-radius);
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      border: none;
    }

    .btn-search {
      background-color: var(--primary);
      color: var(--white);
    }

    .btn-search:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    .btn-reset {
      background-color: var(--white);
      color: var(--dark-gray);
      border: 2px solid var(--medium-gray);
    }

    .btn-reset:hover {
      background-color: var(--light-gray);
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    /* Quiz List */
    .quiz-list {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .quiz-item {
      display: flex;
      align-items: center;
      background: var(--white);
      padding: 20px 25px;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      gap: 20px;
      transition: var(--transition);
    }

    .quiz-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .quiz-name {
      flex: 1;
      padding: 12px 20px;
      font-size: 18px;
      border: 2px solid var(--medium-gray);
      border-radius: var(--border-radius);
      background-color: var(--white);
      font-weight: 500;
    }

    .answer-btn {
      background-color: var(--primary);
      color: var(--white);
      border: none;
      padding: 12px 24px;
      border-radius: var(--border-radius);
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
    }

    .answer-btn:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    /* No Results Message */
    .no-results {
      text-align: center;
      padding: 40px;
      font-size: 18px;
      color: var(--dark-gray);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .container {
        padding: 0 20px;
      }

      h1 {
        font-size: 2rem;
      }

      .filter-form {
        padding: 20px;
      }

      .btn-group {
        flex-direction: column;
      }

      .quiz-item {
        flex-direction: column;
        align-items: stretch;
      }

      header {
        flex-direction: column;
        gap: 15px;
        padding: 15px 20px;
      }

      .center-nav {
        position: static;
        justify-content: center;
      }

      .right-section {
        width: 100%;
        justify-content: center;
      }
    }
  </style>
  <link rel="icon" type="image/png" href="logo_website.png">
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="container">
    <h1>English Quizzes</h1>

    <form method="GET" class="filter-form">
      <input type="text" name="search" placeholder="Search Quizzes" value="<?= htmlspecialchars($search) ?>">

      <select name="difficulty">
        <option value="" disabled <?= empty($difficulty) ? 'selected' : '' ?>>Difficulty</option>
        <option value="Easy" <?= $difficulty == 'Easy' ? 'selected' : '' ?>>Easy</option>
        <option value="Intermediate" <?= $difficulty == 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
        <option value="Advance" <?= $difficulty == 'Advance' ? 'selected' : '' ?>>Advance</option>
      </select>

      <select name="category">
        <option value="" disabled <?= empty($category) ? 'selected' : '' ?>>Category</option>
        <option value="Verb Tenses" <?= $category == 'Verb Tenses' ? 'selected' : '' ?>>Verb Tenses</option>
        <option value="Phrasal Verbs" <?= $category == 'Phrasal Verbs' ? 'selected' : '' ?>>Phrasal Verbs</option>
        <option value="Prepositions" <?= $category == 'Prepositions' ? 'selected' : '' ?>>Prepositions</option>
        <option value="Other Grammar Topics" <?= $category == 'Other Grammar Topics' ? 'selected' : '' ?>>Other Grammar Topics</option>
      </select>

      <div class="btn-group">
        <button type="submit" class="btn-search">Search</button>
        <button type="button" class="btn-reset" id="resetBtn">Reset Filters</button>
      </div>
    </form>

    <?php if ($search || $difficulty || $category): ?>
      <div class="quiz-list">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <div class="quiz-item">
              <input type="text" value="<?= htmlspecialchars($row['QUIZ_TITLE'] . ' - ' . $row['QUIZ_CATEGORY'] . ' - ' . $row['QUIZ_DIFFICULTY']) ?>" readonly class="quiz-name">
              <form action="answer_quiz.php" method="GET">
                <input type="hidden" name="quiz_id" value="<?= $row['QUIZ_ID'] ?>">
                <button type="submit" class="answer-btn">Answer</button>
              </form>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="no-results">No quizzes found matching your criteria.</p>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <?php include 'logout_modal.php'; ?>

  <script>
    document.getElementById('resetBtn').addEventListener('click', function() {
      window.location.href = window.location.pathname;
    });
  </script>
</body>
</html>
<?php $conn->close(); ?>