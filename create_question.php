<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");


if (!isset($_SESSION['quiz_id'])) {
    header("Location: create_quiz.php");
    exit();
}

$quiz_id = $_SESSION['quiz_id'];
$current = $_SESSION['current_question'] ?? 1;
$editing = $_SESSION['editing'] ?? false;



// Handle question navigation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navigate_to'])) {
    $_SESSION['current_question'] = (int)$_POST['navigate_to'];
    header("Location: create_question.php");
    exit();
}


// Load existing questions from database during edit mode
if ($editing && !isset($_SESSION['questions'])) {
    $result = $conn->query("SELECT * FROM QUESTION WHERE QUIZ_ID = " . (int)$quiz_id . " ORDER BY QUESTION_NO ASC");
    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $questions[$row['QUESTION_NO']] = [
            'question' => $row['QUESTION_CONTENT'],
            'a' => $row['QUESTION_OPTION1'],
            'b' => $row['QUESTION_OPTION2'],
            'c' => $row['QUESTION_OPTION3'],
            'd' => $row['QUESTION_OPTION4'],
            'correct' => $row['QUESTION_CORRECT_OPTION'],
            'explanation' => $row['QUESTION_EXPLANATION']
        ];
    }
    $_SESSION['questions'] = $questions;
}

// Ask user to fill everything before going to the next question
// Repeat 10 times so user can put 10 questions.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question     = $_POST['question'] ?? '';
    $a            = $_POST['optionA'] ?? '';
    $b            = $_POST['optionB'] ?? '';
    $c            = $_POST['optionC'] ?? '';
    $d            = $_POST['optionD'] ?? '';
    $correct      = $_POST['answer'] ?? '';
    $explanation  = $_POST['explanation'] ?? '';

    if (empty($question) || empty($a) || empty($b) || empty($c) || empty($d) || empty($correct) || empty($explanation)) {
        $_SESSION['feedback'] = "Please fill in all fields and select a correct answer.";
        header("Location: create_question.php");
        exit();
    }

    $_SESSION['questions'][$current] = [
        'question' => $question,
        'a' => $a,
        'b' => $b,
        'c' => $c,
        'd' => $d,
        'correct' => $correct,
        'explanation' => $explanation
    ];

    if (isset($_POST['next']) && $current < 10) {
        $_SESSION['current_question'] = ++$current;
    } elseif (isset($_POST['previous']) && $current > 1) {
        $_SESSION['current_question'] = --$current;
    } elseif (isset($_POST['submit'])) {
        foreach ($_SESSION['questions'] as $no => $q) {
            if ($editing) {
                $check = $conn->prepare("SELECT 1 FROM QUESTION WHERE QUIZ_ID = ? AND QUESTION_NO = ?");
                $check->bind_param("ii", $quiz_id, $no);
                $check->execute();
                $check->store_result();

                if ($check->num_rows > 0) {
                    $stmt = $conn->prepare("UPDATE QUESTION SET
                        QUESTION_CONTENT = ?, QUESTION_OPTION1 = ?,
                        QUESTION_OPTION2 = ?, QUESTION_OPTION3 = ?,
                        QUESTION_OPTION4 = ?, QUESTION_CORRECT_OPTION = ?,
                        QUESTION_EXPLANATION = ? WHERE QUIZ_ID = ? AND QUESTION_NO = ?");
                    $stmt->bind_param("sssssssii",
                        $q['question'], $q['a'], $q['b'], $q['c'], $q['d'], $q['correct'], $q['explanation'], $quiz_id, $no);
                } else {
                    $stmt = $conn->prepare("INSERT INTO QUESTION (
                        QUIZ_ID, QUESTION_NO, QUESTION_CONTENT,
                        QUESTION_OPTION1, QUESTION_OPTION2,
                        QUESTION_OPTION3, QUESTION_OPTION4,
                        QUESTION_CORRECT_OPTION, QUESTION_EXPLANATION
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("iisssssss",
                        $quiz_id, $no, $q['question'], $q['a'], $q['b'], $q['c'], $q['d'], $q['correct'], $q['explanation']);
                }

                if (!$stmt->execute()) {
                    error_log("Failed to save question #$no: " . $stmt->error);
                }
            } else {
                $stmt = $conn->prepare("INSERT INTO QUESTION (
                    QUIZ_ID, QUESTION_NO, QUESTION_CONTENT,
                    QUESTION_OPTION1, QUESTION_OPTION2,
                    QUESTION_OPTION3, QUESTION_OPTION4,
                    QUESTION_CORRECT_OPTION, QUESTION_EXPLANATION
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iisssssss",
                    $quiz_id, $no, $q['question'], $q['a'], $q['b'], $q['c'], $q['d'], $q['correct'], $q['explanation']);
                if (!$stmt->execute()) {
                    error_log("Insert error question #$no: " . $stmt->error);
                }
            }
        }

        unset($_SESSION['questions'], $_SESSION['current_question'], $_SESSION['editing']);
        $_SESSION['feedback'] = "Quiz Saved Successfully";
        header("Location: edit_quiz.php");
        exit();
    }

    header("Location: create_question.php");
    exit();
}

// To load existing data for edit quiz, prevent code to break
$questionData = $_SESSION['questions'][$current] ?? [
    'question' => '', 'a' => '', 'b' => '', 'c' => '', 'd' => '', 'correct' => '', 'explanation' => ''
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<link rel="icon" type="image/png" href="logo_website.png">
    <title><?= $editing ? 'Edit Question' : 'Create Question' ?></title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        margin: 0;
        padding: 20px;
        min-height: 100vh;
        color: #333;
        box-sizing: border-box;
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .quiz-container {
        animation: fadeIn 0.5s ease-in-out;
        padding-bottom: 20px; /* Added space at bottom */
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    h1 {
        color: #2c3e50;
        margin: 0 0 25px 0;
        padding: 0;
        font-size: 28px;
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
    }

    h3 {
        color: #2c3e50;
        margin: 20px 0 10px 0;
        font-size: 18px;
    }

    .feedback {
        background-color: #ffeaa7;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        color: #d35400;
        font-weight: 500;
    }

    .text-input-question {
        width: 100%;
        padding: 15px;
        margin-bottom: 20px;
        border: 2px solid #dfe6e9;
        border-radius: 10px;
        font-size: 16px;
        transition: all 0.3s;
        box-sizing: border-box;
    }

    .text-input-question:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        outline: none;
    }

    .option {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        background: #f8f9fa;
        padding: 10px 15px;
        border-radius: 10px;
        transition: all 0.3s;
    }

    .option:hover {
        background: #e9f5ff;
    }

    .option input[type="radio"] {
        margin-right: 15px;
        transform: scale(1.3);
        accent-color: #3498db;
    }

    .option label {
        font-weight: bold;
        margin-right: 10px;
        color: #2c3e50;
        min-width: 20px;
    }

    .option .text-input {
        flex-grow: 1;
        padding: 12px;
        border: 2px solid #dfe6e9;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s;
    }

    .option .text-input:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        outline: none;
    }

    .explanation textarea {
        width: 100%;
        padding: 15px;
        border: 2px solid #dfe6e9;
        border-radius: 10px;
        font-size: 16px;
        min-height: 100px;
        resize: vertical;
        transition: all 0.3s;
        box-sizing: border-box;
    }

    .explanation textarea:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        outline: none;
    }

    .button-row {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
    }

    .left-buttons {
        display: flex;
        gap: 10px;
    }

    button {
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    button[type="submit"] {
        background-color: #3498db;
        color: white;
    }

    button[type="submit"]:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    button[type="button"] {
        background-color: #e74c3c;
        color: white;
    }

    button[type="button"]:hover {
        background-color: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    #loading {
        text-align: center;
        margin: 20px 0;
        font-size: 16px;
        color: #3498db;
        font-weight: 600;
        animation: pulse 1.5s infinite;
    }

    .question-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 30px 0 20px 0;
        justify-content: center;
    }

    .question-nav button {
        width: 42px;
        height: 42px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: <?= $editing ? '#f8f9fa' : '#e9ecef' ?>;
        color: #495057;
        border: 1px solid #dee2e6;
        font-weight: 600;
        transition: all 0.2s;
    }

    .question-nav button:hover {
        background-color: #dee2e6;
        transform: translateY(-2px);
    }

    .question-nav button.active {
        background-color: #3498db;
        color: white;
        border-color: #3498db;
        transform: scale(1.05);
    }

    .submit-container {
        text-align: center;
        margin-top: 20px;
        padding-bottom: 20px; /* Added space at bottom */
    }

    .submit-container button {
        background-color: #2ecc71;
        padding: 12px 30px;
        font-size: 17px;
    }

    .submit-container button:hover {
        background-color: #27ae60;
    }

    @keyframes pulse {
        0% { opacity: 0.6; }
        50% { opacity: 1; }
        100% { opacity: 0.6; }
    }

    @media (max-width: 768px) {
        .container {
            margin: 15px auto;
            padding: 20px;
        }
        
        .button-row {
            flex-direction: column;
            gap: 10px;
        }
        
        .left-buttons {
            width: 100%;
            justify-content: space-between;
        }
        
        button {
            width: 100%;
        }
        
        .question-nav {
            gap: 6px;
        }
        
        .question-nav button {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }
    }
    </style>
</head>
<body>
<div class="container">
    <div class="quiz-container">
        <h1><?= $editing ? 'Question' : 'Question' ?> <?= $current ?></h1>

        <?php if (isset($_SESSION['feedback'])): ?>
            <div class="feedback">
                <?= htmlspecialchars($_SESSION['feedback']) ?>
                <?php unset($_SESSION['feedback']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" onsubmit="return validateForm()">
            <input type="text" name="question" class="text-input-question" placeholder="Put question here" value="<?= htmlspecialchars($questionData['question']) ?>" required>

            <?php
            $options = ['A', 'B', 'C', 'D'];
            foreach ($options as $opt) {
                $value = htmlspecialchars($questionData[strtolower($opt)]);
                $checked = ($questionData['correct'] === $opt) ? 'checked' : '';
                echo "<div class='option'>
                    <input type='radio' name='answer' value='$opt' id='opt$opt' $checked required>
                    <label for='opt$opt'>$opt.</label>
                    <input type='text' name='option$opt' class='text-input' value='$value' required>
                </div>";
            }
            ?>

            <div class="explanation">
                <h3>Explanation of Answer</h3>
                <textarea name="explanation" placeholder="Put explanation here" required><?= htmlspecialchars($questionData['explanation']) ?></textarea>
            </div>

            <div id="loading" style="display:none; color:blue;">Saving question... please wait.</div>

            <div class="button-row">
                <div class="left-buttons">
                    <?php if ($current > 1): ?>
                        <button type="submit" name="previous">Previous</button>
                    <?php endif; ?>
                    <?php if ($current < 10): ?>
                        <button type="submit" name="next">Next</button>
                    <?php endif; ?>
                </div>
                <button type="button" onclick="confirmCancel()">Cancel</button>
            </div>

            <div class="question-nav">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <button type="button" onclick="navigateToQuestion(<?= $i ?>)" class="<?= $i == $current ? 'active' : '' ?>">
                        <?= $i ?>
                    </button>
                <?php endfor; ?>
            </div>

            <div class="submit-container">
                <button type="submit" name="submit">Submit All Questions</button>
            </div>
        </form>
    </div>
</div>
<!-- Previous PHP code remains exactly the same until the JavaScript section -->
<!-- All your previous PHP and HTML code remains the same until the JavaScript section -->
<script>
function confirmCancel() {
    const isEditing = <?= isset($_SESSION['editing']) && $_SESSION['editing'] ? 'true' : 'false' ?>;
    
    if (isEditing) {
        if (confirm("Are you sure you want to cancel editing? Any unsaved changes will be lost.")) {
            window.location.href = "edit_quiz.php";
        }
    } else {
        if (confirm("Are you sure you want to cancel creating this quiz? All questions will be discarded.")) {
            window.location.href = "deleteCreating_question.php";
        }
    }
}

function validateForm() {
    const question = document.querySelector('[name="question"]').value.trim();
    const options = ['A', 'B', 'C', 'D'].map(opt => document.querySelector(`[name="option${opt}"]`).value.trim());
    const explanation = document.querySelector('[name="explanation"]').value.trim();
    const answer = document.querySelector('[name="answer"]:checked');

    if (!question || options.includes('') || !explanation || !answer) {
        alert("All fields must be filled and one correct answer selected.");
        return false;
    }

    document.getElementById('loading').style.display = 'block';
    return true;
}

// ========== PERSISTENT POPUP NOTIFICATION ==========
// Create and style the popup element
const popup = document.createElement('div');
Object.assign(popup.style, {
    position: 'fixed',
    top: '30px',
    left: '50%',
    transform: 'translateX(-50%)',
    backgroundColor: '#ff4444',
    color: 'white',
    padding: '18px 30px',
    borderRadius: '8px',
    boxShadow: '0 4px 15px rgba(0,0,0,0.2)',
    zIndex: '9999',
    display: 'none',
    fontFamily: 'Segoe UI, Tahoma, Geneva, Verdana, sans-serif',
    fontSize: '16px',
    fontWeight: '500',
    transition: 'opacity 0.6s ease',
    maxWidth: '90%',
    textAlign: 'center',
    lineHeight: '1.5'
});
popup.innerHTML = '⚠️ Warning: Your changes are not saved.<br>Please use Next/Previous buttons to save.';
document.body.appendChild(popup);

// Check if we should show popup from previous page
if (localStorage.getItem('showUnsavedWarning')) {
    popup.style.display = 'block';
    popup.style.opacity = '1';
    
    setTimeout(() => {
        popup.style.opacity = '0';
        setTimeout(() => {
            popup.style.display = 'none';
            localStorage.removeItem('showUnsavedWarning');
        }, 500);
    }, 3000);
}

function navigateToQuestion(questionNumber) {
    // Set flag in localStorage before navigating
    localStorage.setItem('showUnsavedWarning', 'true');
    
    // Navigation logic
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'create_question.php';
    
    const navigateInput = document.createElement('input');
    navigateInput.type = 'hidden';
    navigateInput.name = 'navigate_to';
    navigateInput.value = questionNumber;
    form.appendChild(navigateInput);
    
    document.body.appendChild(form);
    form.submit();
}
</script>
</body>
</html>