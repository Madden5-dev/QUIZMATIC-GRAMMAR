<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");

// Fetch all quizzes
$quizResult = $conn->query("SELECT * FROM QUIZ ORDER BY QUIZ_DATE DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" type="image/png" href="logo_website.png">
    <title>Delete Quiz</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #00bcd4;
            --secondary-color: #0097a7;
            --danger-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
		font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }


        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h1 {
            color: var(--dark-color);
            margin-bottom: 10px;
            font-weight: 600;
        }

        h3 {
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 25px;
        }

        .search-section {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

        .search-box {
            flex: 1;
            padding: 12px 20px;
            border: 1px solid #dee2e6;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
        }

        .search-box:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .search-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
        }

        .search-btn:hover {
            background-color: var(--secondary-color);
        }

        .quiz-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .quiz-item {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
        }

        .quiz-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .quiz-info {
            flex: 1;
        }

        .quiz-title {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .quiz-meta {
            color: #6c757d;
            font-size: 14px;
        }

        .delete-btn {
            background-color: var(--danger-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .delete-btn:hover {
            background-color: #d6336c;
            transform: translateY(-2px);
        }

        .back-btn {
            margin-top: 30px;
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
        }

        .back-btn:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .highlight {
            background-color: #fff3cd;
            transition: var(--transition);
        }

        .no-results {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">

	<button class="back-btn"  onclick="window.location.href='quiz_homepage.php'">
            <i class="fas fa-arrow-left"></i> Back
        </button>

        <h1>Delete Quiz</h1>
        <h3>Search which quiz to delete</h3>
        
        <div class="search-section">
            <input type="text" id="searchBox" class="search-box" placeholder="e.g. Advanced Vocabulary">
            <button class="search-btn" onclick="searchQuiz()">
                <i class="fas fa-search"></i> Search
            </button>
        </div>

        <div class="quiz-list" id="quizList">
            <?php if ($quizResult->num_rows > 0): ?>
                <?php while ($quiz = $quizResult->fetch_assoc()): ?>
                    <div class="quiz-item" data-title="<?= htmlspecialchars(strtolower($quiz['QUIZ_TITLE'])) ?>">
                        <div class="quiz-info">
                            <div class="quiz-title"><?= htmlspecialchars($quiz['QUIZ_TITLE']) ?></div>
                            <div class="quiz-meta">
                                <?= htmlspecialchars($quiz['QUIZ_CATEGORY']) ?> • <?= htmlspecialchars($quiz['QUIZ_DIFFICULTY']) ?>
                            </div>
                        </div>
                        <button class="delete-btn" onclick="confirmDelete('<?= htmlspecialchars($quiz['QUIZ_TITLE']) ?>', <?= $quiz['QUIZ_ID'] ?>, this.parentNode)">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-results">
                    No quizzes found. Create a new quiz to get started.
                </div>
            <?php endif; ?>
        </div>

        
    </div>


        <script src="delete_quiz.js"></script>
 

    <?php include 'logout_modal.php'; ?>
</body>
</html>