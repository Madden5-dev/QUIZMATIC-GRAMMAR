<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");

// Handle search if search parameter exists
$searchResults = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $conn->real_escape_string($_GET['search']);
    $searchQuery = $conn->query("SELECT * FROM QUIZ WHERE QUIZ_TITLE LIKE '%$searchTerm%' ORDER BY QUIZ_DATE DESC");
    while ($row = $searchQuery->fetch_assoc()) {
        $searchResults[] = $row;
    }
}

// Get all quizzes
$allQuizzes = $conn->query("SELECT * FROM QUIZ ORDER BY QUIZ_DATE DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="logo_website.png">
    <title>Edit Quiz</title>
    <style>
        :root {
            --primary-color: #00bcd4;
            --primary-hover: #0097a7;
            --secondary-color: #6c757d;
            --secondary-hover: #5a6268;
            --danger-color: #dc3545;
            --danger-hover: #c82333;
            --success-color: #28a745;
            --success-hover: #218838;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
            margin-bottom: 30px;
        }
        
        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .quiz-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .quiz-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .quiz-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .quiz-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: #7f8c8d;
        }
        
        .quiz-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: var(--secondary-hover);
        }
        
        .search-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .search-box {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 300px;
            font-size: 16px;
            margin-right: 10px;
        }
        
        .search-btn {
            padding: 10px 20px;
        }
        
        .update-section {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            margin-top: 30px;
            display: none;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .modal-text {
            margin-bottom: 20px;
            font-size: 1.1rem;
        }
        
        .modal-btn {
            padding: 8px 20px;
            float: right;
        }
        
        .search-results {
            margin-bottom: 30px;
        }
        
        .search-results-title {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: #2c3e50;
            border-bottom: 2px solid #00bcd4;
            padding-bottom: 8px;
        }
        
        .clear-search {
            color: #00bcd4;
            text-decoration: none;
            margin-left: 15px;
            font-size: 0.9rem;
        }
        
        .clear-search:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

<div class="container">
    <h1>Manage Quizzes</h1>
    
    <div class="search-section">
        <h2>Search Quiz</h2>
        <form method="GET" action="edit_quiz.php" id="searchForm">
            <input type="text" id="searchTitle" name="search" class="search-box" placeholder="Enter quiz title" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit" class="btn btn-primary search-btn">Search</button>
            <?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
                <a href="edit_quiz.php" class="clear-search">Clear search</a>
            <?php endif; ?>
        </form>
    </div>
    
    <?php if(!empty($searchResults)): ?>
        <div class="search-results">
            <h2 class="search-results-title">Search Results for "<?= htmlspecialchars($_GET['search']) ?>"</h2>
            <div class="quiz-grid">
                <?php foreach($searchResults as $quiz): ?>
                    <div class="quiz-card">
                        <div class="quiz-title"><?= htmlspecialchars($quiz['QUIZ_TITLE']) ?></div>
                        <div class="quiz-meta">
                            <span><?= htmlspecialchars($quiz['QUIZ_CATEGORY']) ?></span>
                            <span><?= htmlspecialchars($quiz['QUIZ_DIFFICULTY']) ?></span>
                            <span><?= date('M d, Y', strtotime($quiz['QUIZ_DATE'])) ?></span>
                        </div>
                        <div class="quiz-actions">
                            <button type="button" class="btn btn-primary" onclick="loadQuizForEdit(<?= $quiz['QUIZ_ID'] ?>)">Edit Quiz</button>
                            <form method="POST" action="editRedirect_quiz.php" style="display:inline;">
                                <input type="hidden" name="quiz_id" value="<?= $quiz['QUIZ_ID'] ?>">
                                <button type="submit" class="btn btn-secondary">Edit Questions</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <div id="updateForm" class="update-section">
        <h2>Update Quiz Details</h2>
        <form id="updateQuizForm" onsubmit="return submitUpdate(event)">
            <input type="hidden" name="quiz_id" id="quiz_id">
            
            <div class="form-group">
                <label class="form-label" for="newTitle">Quiz Title</label>
                <input type="text" class="form-control" id="newTitle" name="new_title" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="quiz_category">Category</label>
                <select class="form-control" id="quiz_category" name="quiz_category" required>
                    <option value="Verb Tenses">Verb Tenses</option>
                    <option value="Phrasal Verbs">Phrasal Verbs</option>
                    <option value="Prepositions">Prepositions</option>
                    <option value="Other Grammar Topics">Other Grammar Topics</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="quiz_difficulty">Difficulty Level</label>
                <select class="form-control" id="quiz_difficulty" name="quiz_difficulty" required>
                    <option value="Easy">Easy</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advance">Advance</option>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('updateForm').style.display='none'">Cancel</button>
            </div>
        </form>
        
        <div class="quiz-actions">
            <form method="POST" action="editRedirect_quiz.php" id="goToQuestionForm">
                <input type="hidden" name="quiz_id" id="goToQuestionQuizId">
            </form>
        </div>
    </div>
    
    <?php if(empty($searchResults) || !isset($_GET['search']) || empty($_GET['search'])): ?>
        <h2>All Quizzes</h2>
        <div class="quiz-grid">
            <?php while ($quiz = $allQuizzes->fetch_assoc()): ?>
                <div class="quiz-card">
                    <div class="quiz-title"><?= htmlspecialchars($quiz['QUIZ_TITLE']) ?></div>
                    <div class="quiz-meta">
                        <span><?= htmlspecialchars($quiz['QUIZ_CATEGORY']) ?></span>
                        <span><?= htmlspecialchars($quiz['QUIZ_DIFFICULTY']) ?></span>
                        <span><?= date('M d, Y', strtotime($quiz['QUIZ_DATE'])) ?></span>
                    </div>
                    <div class="quiz-actions">
                        <button type="button" class="btn btn-primary" onclick="loadQuizForEdit(<?= $quiz['QUIZ_ID'] ?>)">Edit Quiz</button>
                        <form method="POST" action="editRedirect_quiz.php" style="display:inline;">
                            <input type="hidden" name="quiz_id" value="<?= $quiz['QUIZ_ID'] ?>">
                            <button type="submit" class="btn btn-secondary">Edit Questions</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<div class="modal" id="popupModal">
    <div class="modal-content">
        <p class="modal-text" id="popupText"></p>
        <button class="btn btn-primary modal-btn" onclick="closeModal()">OK</button>
    </div>
</div>

<script src="edit_quiz.js"></script>

<?php include 'logout_modal.php'; ?>

</body>
</html>