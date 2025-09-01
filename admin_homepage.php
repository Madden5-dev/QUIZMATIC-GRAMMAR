<?php
session_start();

$host = "localhost";
$dbname = "quizmaticgrammar";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get total users
$userResult = $conn->query("SELECT COUNT(*) AS total FROM USER WHERE user_type != 'administrator'");
$userTotal = $userResult->fetch_assoc()['total'];

// Get total quizzes
$quizResult = $conn->query("SELECT COUNT(*) AS total FROM QUIZ");
$quizTotal = $quizResult->fetch_assoc()['total'];

// Get all quizzes
$quizzes = [];
$result = $conn->query("SELECT * FROM QUIZ ORDER BY QUIZ_DATE DESC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $quizzes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" type="image/png" href="logo_website.png">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
            --warning-color: #f8961e;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --text-color: #495057;
            --border-radius: 12px;
            --box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7ff;
            margin: 0;
            padding: 0;
            color: var(--text-color);
            line-height: 1.6;
        }

        .quiz-table-wrapper {
            max-height: 400px;
            overflow-y: auto;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-top: 20px;
            background: white;
        }

        .quiz-table-wrapper::-webkit-scrollbar {
            width: 8px;
        }

        .quiz-table-wrapper::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .quiz-table-wrapper::-webkit-scrollbar-thumb {
            background: var(--accent-color);
            border-radius: 10px;
        }

        .quiz-table-wrapper thead th {
            position: sticky;
            top: 0;
            background-color: var(--primary-color);
            color: white;
            z-index: 10;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 15px;
        }

        .user-info {
            display: flex;
            gap: 20px;
            padding-top: 30px;
            max-width: 1000px;
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
        }

        .info-quiz, .info-user {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            font-size: 1.1rem;
            width: 50%;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .info-quiz:hover, .info-user:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(67, 97, 238, 0.2);
        }

        .info-quiz {
            background: linear-gradient(135deg, var(--success-color), #38a3d1);
        }

        .container-Quizzes {
            background-color: #ffffff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 25px;
            height: auto;
            max-width: 1000px;
            width: 100%;
            margin: 30px auto;
            box-sizing: border-box;
        }

        .container-Quizzes h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 1.8rem;
            position: relative;
            padding-bottom: 10px;
        }

        .container-Quizzes h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        td {
            color: var(--text-color);
            font-weight: 500;
        }

        tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }

        .view-btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            text-align: center;
            min-width: 80px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            font-size: 0.85rem;
        }

        /* Comment VIEW button */
        td:nth-child(5) .view-btn {
            background-color: var(--accent-color);
            color: white;
            border: 2px solid var(--accent-color);
        }

        td:nth-child(5) .view-btn:hover {
            background-color: #3a7bd5;
            border-color: #3a7bd5;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(72, 149, 239, 0.3);
        }

        /* Leaderboard VIEW button */
        td:nth-child(6) .view-btn {
            background-color: var(--success-color);
            color: white;
            border: 2px solid var(--success-color);
        }

        td:nth-child(6) .view-btn:hover {
            background-color: #3bb4d8;
            border-color: #3bb4d8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 201, 240, 0.3);
        }

        /* Active state for both buttons */
        .view-btn:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .difficulty-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .difficulty-easy {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .difficulty-intermediate {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .difficulty-advance {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        @media (max-width: 768px) {
            .user-info {
                flex-direction: column;
            }
            
            .info-quiz, .info-user {
                width: 100%;
            }
            
            .container-Quizzes {
                padding: 15px;
            }
            
            th, td {
                padding: 10px 8px;
                font-size: 0.9rem;
            }
        }
	
	.feedback-btn {
	display: inline-block;
	padding: 12px 24px; 
	background-color: #4361ee; 
	color: white; 
	border-radius: 30px; 
	text-decoration: none; 
	box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3); 
	font-weight: 500; 
	transition: all 0.3s ease;
	}
	
    </style>
    <title>Admin Homepage</title>
</head>

<body>
<?php include 'header.php'; ?>

<main>
    <form id="adminHome-form" method="POST">
        <div class="user-info">
            <div class="info-user" id="totUser">
                <span style="margin-right: 10px; font-size: 1.4rem;">👤</span>
                <div>
                    <div style="font-size: 1.8rem; font-weight: bold;"><?= $userTotal ?></div>
                    <div style="font-size: 0.9rem;">Total Users</div>
                </div>
            </div>
            <div class="info-quiz" id="totQuestion">
                <span style="margin-right: 10px; font-size: 1.4rem;">❓</span>
                <div>
                    <div style="font-size: 1.8rem; font-weight: bold;"><?= $quizTotal ?></div>
                    <div style="font-size: 0.9rem;">Total Quizzes</div>
                </div>
            </div>
        </div>

        <div class="container-Quizzes">
            <h2>Quizzes</h2>

            <div class="quiz-table-wrapper">
                <table id="quizTable">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Difficulty</th>
                            <th>Launched Date</th>
                            <th>Comments</th>
                            <th>Leaderboard</th>
                        </tr>
                    </thead>
                    <tbody id="quizTableBody">
                        <?php foreach ($quizzes as $quiz): 
                            $difficultyClass = '';
                            if ($quiz['QUIZ_DIFFICULTY'] == 'Easy') {
                                $difficultyClass = 'difficulty-easy';
                            } elseif ($quiz['QUIZ_DIFFICULTY'] == 'Intermediate') {
                                $difficultyClass = 'difficulty-intermediate';
                            } elseif ($quiz['QUIZ_DIFFICULTY'] == 'Advance') {
                                $difficultyClass = 'difficulty-advance';
                            }
                        ?>
                            <tr class="quiz-row" id="quiz-row-<?= $quiz['QUIZ_ID'] ?>">
                                <td><?= htmlspecialchars($quiz['QUIZ_TITLE']) ?></td>
                                <td><?= htmlspecialchars($quiz['QUIZ_CATEGORY']) ?></td>
                                <td><span class="difficulty-badge <?= $difficultyClass ?>"><?= htmlspecialchars($quiz['QUIZ_DIFFICULTY']) ?></span></td>
                                <td><?= htmlspecialchars(date("d/m/Y", strtotime($quiz['QUIZ_DATE']))) ?></td>
                                <td>
                                    <a href="comment.php?quiz_id=<?= $quiz['QUIZ_ID'] ?>" class="view-btn">VIEW</a>
                                </td>
                                <td>
                                    <a href="leaderboard.php?quiz_id=<?= $quiz['QUIZ_ID'] ?>" class="view-btn">VIEW</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</main>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Add animation to table rows
    const rows = document.querySelectorAll('.quiz-row');
    rows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        row.style.transition = `opacity 0.3s ease, transform 0.3s ease ${index * 0.05}s`;
        
        setTimeout(() => {
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, 100);
    });
});
</script>

<div style="position: fixed; bottom: 20px; right: 20px;">
    <a href="admin_feedback.php" class=feedback-btn>
        Feedback
        <span style="margin-left: 8px;">📢</span>
    </a>
</div>

<?php include 'logout_modal.php'; ?>
</body>
</html>

<?php include 'logout_modal.php'; ?>
</body>
</html>