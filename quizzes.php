<?php
session_start();
$isLoggedIn = isset($_SESSION['USER_NAME']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>English Quizzes</title>
    <style>
        /* Modern CSS Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* Color Variables */
        :root {
            --primary: #00c3e3;
            --primary-dark: #009bb5;
            --primary-light: #e6f9fd;
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

       
        .search-btn, 
        .back-btn,
        .answer-btn {
            background-color: var(--primary);
            color: var(--white);
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }



        .search-btn:hover,
        .back-btn:hover,
        .answer-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        /* Quiz Header */
        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 40px 50px 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--medium-gray);
        }

        .quiz-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-gray);
            margin: 0;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .quiz-buttons {
            display: flex;
            gap: 15px;
        }

        /* Quiz List */
        .quiz-list {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto 40px;
            padding: 0 20px;
        }

        .quiz-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
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
            transition: var(--transition);
        }

        .quiz-name:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 195, 227, 0.2);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .quiz-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
                margin: 30px 20px;
            }

            .quiz-item {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }

            .quiz-name {
                width: 100%;
            }

            

            
        }
    </style>
    <link rel="icon" type="image/png" href="logo_website.png">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="quiz-header">
        <h1>English Quizzes</h1>
        <div class="quiz-buttons">
            <button class="search-btn" onclick="window.location.href='quiz_search.php'">
                Search
                <img src="searchLogo.png" alt="Search" style="width: 16px; height: 16px; vertical-align: middle; margin-left: 6px;" />
            </button>
            <button class="back-btn" onclick="window.location.href='home_redirect.php'">Back</button>
        </div>
    </div>

<?php
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
SELECT QUIZ_ID, QUIZ_TITLE, QUIZ_CATEGORY, QUIZ_DIFFICULTY
FROM quiz
ORDER BY
  CASE QUIZ_DIFFICULTY
    WHEN 'Easy' THEN 1
    WHEN 'Intermediate' THEN 2
    WHEN 'Advance' THEN 3
    ELSE 4
  END,
  QUIZ_CATEGORY ASC,
  QUIZ_TITLE ASC
";

$result = $conn->query($sql);
?>

<div class="quiz-list">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="quiz-item">
            <input type="text" value="<?= htmlspecialchars($row['QUIZ_TITLE'] . ' - ' . $row['QUIZ_CATEGORY'] . ' - ' . $row['QUIZ_DIFFICULTY']) ?>" readonly class="quiz-name">
            <form action="answer_quiz.php" method="GET">
                <input type="hidden" name="quiz_id" value="<?= $row['QUIZ_ID'] ?>">
                <button type="submit" class="answer-btn">Answer</button>
            </form>
        </div>
    <?php endwhile; ?>
</div>

<?php include 'logout_modal.php'; ?>

<?php include 'footer.php'; ?>

<?php $conn->close(); ?>
</body>
</html>