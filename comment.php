<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$quizId = $_GET['quiz_id'] ?? 0;
$userId = $_SESSION['user']['USER_ID'] ?? 0;
$isAdmin = ($_SESSION['user']['USER_TYPE'] ?? '') === 'administrator';

// Handle posting comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'])) {
    $text = $conn->real_escape_string($_POST['comment_text']);
    $parentId = $_POST['parent_comment_id'] ?? "NULL";

    $conn->query("INSERT INTO comment (COMMENT_TEXT, QUIZ_ID, USER_ID, PARENT_COMMENT_ID)
                  VALUES ('$text', '$quizId', '$userId', " . ($parentId ?: "NULL") . ")");
    $commentId = $conn->insert_id;
    $now = date("Y-m-d H:i:s");
    $conn->query("INSERT INTO user_comment (COMMENT_ID, USER_ID, DATE_POST)
                  VALUES ('$commentId', '$userId', '$now')");
}

// Handle comment deletion
if ($isAdmin && isset($_GET['delete_comment'])) {
    $commentId = intval($_GET['delete_comment']);
    $conn->query("DELETE FROM comment WHERE COMMENT_ID = $commentId");
    header("Location: comment.php?quiz_id=$quizId");
    exit();
}

// Fetch comments
function fetchComments($conn, $quizId) {
    $sql = "
      SELECT c.*, u.USER_NAME, u.USER_TYPE
      FROM comment c
      JOIN user u ON u.USER_ID = c.USER_ID
      WHERE c.QUIZ_ID = '$quizId'
      ORDER BY c.PARENT_COMMENT_ID ASC, c.COMMENT_ID ASC";
    $res = $conn->query($sql);
    $comments = [];
    while ($row = $res->fetch_assoc()) {
        $comments[] = $row;
    }
    return $comments;
}

// Recursive comment rendering
function renderComments($comments, $parentId = null, $level = 0, $isAdmin = false) {
    foreach ($comments as $comment) {
        if ((string)$comment['PARENT_COMMENT_ID'] === (string)$parentId) {
            $marginLeft = 30 * $level;
            $isCommenterAdmin = $comment['USER_TYPE'] === 'administrator';
            $adminClass = $isCommenterAdmin ? 'admin-comment' : '';
            $adminNameStyle = $isCommenterAdmin ? 'admin-name' : 'user-name';

            echo "<div class='comment-wrapper' style='margin-left: {$marginLeft}px;'>
                    <div class='comment-row $adminClass'>
                      <div class='comment-name $adminNameStyle'>" . htmlspecialchars($comment['USER_NAME']) . "</div>
                      <div class='comment-text'>" . htmlspecialchars($comment['COMMENT_TEXT']) . "</div>
                      <div class='comment-actions'>";
            
            if ($isAdmin) {
                echo "<button class='delete-btn' onclick=\"if(confirm('Delete this comment?')){ window.location.href='comment.php?quiz_id={$comment['QUIZ_ID']}&delete_comment={$comment['COMMENT_ID']}'; }\">Delete</button>";
            }
            
            echo "<button class='reply-btn' onclick=\"toggleReplyForm({$comment['COMMENT_ID']})\">Reply</button>
                      </div>
                    </div>

                    <form method='post' id='replyForm{$comment['COMMENT_ID']}' class='reply-form' style='display:none;'>
                      <input type='hidden' name='parent_comment_id' value='{$comment['COMMENT_ID']}' />
                      <div class='reply-input-group'>
                        <input type='text' name='comment_text' placeholder='Write a reply...' required />
                        <button type='submit'>Post</button>
                      </div>
                    </form>
                  </div>";
            renderComments($comments, $comment['COMMENT_ID'], $level + 1, $isAdmin);
        }
    }
}

$allComments = fetchComments($conn, $quizId);
$quizTitle = $conn->query("SELECT QUIZ_TITLE FROM quiz WHERE QUIZ_ID = '$quizId'")->fetch_assoc()['QUIZ_TITLE'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Comment Section</title>
  <link rel="icon" type="image/png" href="logo_website.png">
  <style>
    :root {
      --primary: #00c3e3;
      --primary-dark: #009bb5;
      --admin-color: #ff9500;
      --admin-dark: #e68600;
      --white: #ffffff;
      --light-gray: #f8f8f8;
      --medium-gray: #e0e0e0;
      --dark-gray: #333333;
      --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      --border-radius: 8px;
      --transition: all 0.3s ease;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--light-gray);
      padding: 20px;
      color: var(--dark-gray);
      line-height: 1.6;
    }

    .main-comment-box {
      max-width: 800px;
      margin: 0 auto;
      background: var(--white);
      padding: 30px;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
    }

    h2 {
      color: var(--dark-gray);
      margin-bottom: 25px;
      font-size: 1.8rem;
      border-bottom: 2px solid var(--medium-gray);
      padding-bottom: 10px;
    }

    .comment-box {
      display: flex;
      gap: 15px;
      margin-bottom: 30px;
    }

    .comment-box input[type="text"] {
      flex: 1;
      padding: 12px 20px;
      border-radius: 30px;
      border: 2px solid var(--medium-gray);
      font-size: 16px;
      transition: var(--transition);
    }

    .comment-box input[type="text"]:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(0, 195, 227, 0.2);
    }

    .comment-box button {
      padding: 12px 20px;
      border: none;
      border-radius: 30px;
      background-color: var(--primary);
      color: var(--white);
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .comment-box button:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
    }

    .comment-wrapper {
      margin-top: 15px;
    }

    .comment-row {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 15px;
      background: var(--white);
      border: 1px solid var(--medium-gray);
      border-radius: var(--border-radius);
      transition: var(--transition);
    }

    .comment-row:hover {
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .admin-comment {
      border-left: 4px solid var(--admin-color);
    }

    .comment-name {
      padding: 8px 12px;
      border-radius: var(--border-radius);
      font-weight: 600;
      white-space: nowrap;
    }

    .user-name {
      background-color: var(--light-gray);
      color: var(--dark-gray);
    }

    .admin-name {
      background-color: var(--admin-color);
      color: var(--white);
    }

    .comment-text {
      flex: 1;
      word-break: break-word;
    }

    .comment-actions {
      display: flex;
      gap: 10px;
    }

    .reply-btn, .delete-btn {
      padding: 8px 15px;
      border: none;
      border-radius: var(--border-radius);
      font-weight: 500;
      cursor: pointer;
      transition: var(--transition);
      white-space: nowrap;
    }

    .reply-btn {
      background-color: var(--primary);
      color: var(--white);
    }

    .reply-btn:hover {
      background-color: var(--primary-dark);
    }

    .delete-btn {
      background-color: #ff4d4d;
      color: var(--white);
    }

    .delete-btn:hover {
      background-color: #e60000;
    }

    .reply-form {
      margin-top: 15px;
      margin-left: 30px;
    }

    .reply-input-group {
      display: flex;
      gap: 10px;
    }

    .reply-input-group input[type="text"] {
      flex: 1;
      padding: 10px 15px;
      border: 1px solid var(--medium-gray);
      border-radius: 30px;
      font-size: 14px;
    }

    .reply-input-group button {
      padding: 10px 20px;
      background-color: var(--primary);
      color: var(--white);
      border: none;
      border-radius: 30px;
      cursor: pointer;
      transition: var(--transition);
    }

    .reply-input-group button:hover {
      background-color: var(--primary-dark);
    }

    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      background-color: var(--primary);
      color: var(--white);
      border: none;
      border-radius: var(--border-radius);
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      margin-bottom: 20px;
      text-decoration: none;
    }

    .back-btn:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
    }

    @media (max-width: 768px) {
      .main-comment-box {
        padding: 20px;
      }
      
      .comment-row {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .comment-actions {
        width: 100%;
        justify-content: flex-end;
      }
    }
  </style>
  <script>
    function toggleReplyForm(id) {
      const form = document.getElementById('replyForm' + id);
      form.style.display = form.style.display === 'block' ? 'none' : 'block';
    }
  </script>
</head>
<body>
<div class="main-comment-box">
  <div>
    <a href="<?= $isAdmin ? 'admin_homepage.php' : 'result_quiz.php' ?>">
      <button class="back-btn" type="button">
        <?= $isAdmin ? 'Back to Homepage' : 'Back to Result' ?>
      </button>
    </a>
  </div>
  
  <h2>Quiz Title: <?= htmlspecialchars($quizTitle) ?></h2>

  <form method="POST" class="comment-box">
    <input type="text" name="comment_text" placeholder="Add comment..." required />
    <button type="submit" class="post-btn">
      <img src="send_icon.png" alt="send" style="width: 16px; height: 16px;" />
      Post
    </button>
  </form>

  <div class="comments-container">
    <?php renderComments($allComments, null, 0, $isAdmin); ?>
  </div>
</div>
</body>
</html>