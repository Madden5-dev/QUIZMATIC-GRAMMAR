<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$quizId = $_GET['quiz_id'] ?? 0;
if (!$quizId) die("No quiz selected.");

$sqlQuiz = "SELECT * FROM quiz WHERE QUIZ_ID = $quizId";
$quizInfo = $conn->query($sqlQuiz)->fetch_assoc();

$sqlQuestions = "SELECT * FROM question WHERE QUIZ_ID = $quizId ORDER BY QUESTION_NO ASC";
$questions = $conn->query($sqlQuestions);

$questionArray = [];
while ($row = $questions->fetch_assoc()) {
    $questionArray[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Answer Quiz | Quizmatic Grammar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="logo_website.png">
  <style>
  :root {
    --primary: #00c3e3;
    --primary-dark: #00a9c7;
    --primary-light: #80e1f1;
    --success: #4cc9f0;
    --danger: #ef233c;
    --dark: #2b2d42;
    --light: #f8f9fa;
    --white: #ffffff;
    --border-radius: 12px;
    --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
  }

  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  body {
    font-family: arial, sans-serif;
    background-color: #f5f7fa;
    color: var(--dark);
    line-height: 1.6;
    padding: 20px;
  }

  .quiz-container {
    max-width: 800px;
    margin: 0px auto;
    padding: 20px;
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    position: relative;
    overflow: hidden;
  }

  .quiz-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
    background: var(--primary);
  }

  .quiz-instruction {
    font-style: italic;
    color: #6c757d;
    margin-bottom: 20px;
    text-align: center;
    font-size: 0.95rem;
  }

  #question-area {
    margin-bottom: 25px;
  }

  .question-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: var(--dark);
    line-height: 1.4;
  }

  .options-container {
    display: grid;
    gap: 12px;
  }

  .option {
    padding: 16px 20px;
    border: 2px solid #e9ecef;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
    font-weight: 500;
    display: flex;
    align-items: center;
    position: relative;
  }

  .option:hover {
    border-color: var(--primary-light);
    transform: translateX(5px);
  }

  .option::before {
    content: attr(data-opt);
    font-weight: bold;
    margin-right: 12px;
    color: var(--primary);
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid var(--primary);
    border-radius: 50%;
  }

  .option.selected {
    background-color: #e6f7ff;
    border-color: var(--primary);
  }

  .option.correct {
    background-color: #e6ffed;
    border-color: #28a745;
  }

  .option.correct::before {
    content: '✓';
    color: #28a745;
    border-color: #28a745;
  }

  .option.wrong {
    background-color: #ffebee;
    border-color: var(--danger);
  }

  .option.wrong::before {
    content: '✗';
    color: var(--danger);
    border-color: var(--danger);
  }

  .btn-group {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
    gap: 15px;
  }

  button {
    padding: 12px 24px;
    border: none;
    border-radius: var(--border-radius);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  #submitBtn {
    background-color: var(--primary);
    color: var(--white);
    box-shadow: 0 4px 12px rgba(0, 195, 227, 0.3);
  }

  #submitBtn:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 195, 227, 0.4);
  }

  #nextBtn {
    background-color: var(--primary);
    color: var(--white);
  }

  #nextBtn:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
  }

  .explanation-box {
    margin-top: 25px;
    padding: 18px;
    background-color: #f8f9fa;
    border-radius: var(--border-radius);
    border-left: 4px solid var(--primary);
    animation: fadeIn 0.5s ease;
  }

  .explanation-box strong {
    color: var(--primary);
  }

  .score-display {
    font-weight: bold;
    margin-top: 10px;
    padding: 8px;
    border-radius: var(--border-radius);
    text-align: center;
  }

  .score-positive {
    color: #28a745;
    background-color: #e6ffed;
  }

  .score-negative {
    color: var(--danger);
    background-color: #ffebee;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @media (max-width: 768px) {
    .quiz-container {
      width: 95%;
      padding: 20px;
    }
    
    .btn-group {
      flex-direction: column;
    }
    
    .question-title {
      font-size: 1.1rem;
    }
  }
  </style>
</head>
<body>
  <div class="quiz-container">
    <p class="quiz-instruction">Choose a correct answer and click submit answer.</p>
    <div id="question-area"></div>
    <div id="explanation" class="explanation-box" style="display:none;"></div>

    <div class="btn-group">
      <button id="submitBtn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        <span>Submit Answer</span>
      </button>
      <button id="nextBtn" style="display:none;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M5 12h14M12 5l7 7-7 7"></path>
        </svg>
        <span>Next Question</span>
      </button>
    </div>

    <form id="finalScoreForm" action="result_quiz.php" method="POST" style="display:none;">
      <input type="hidden" name="score" id="finalScoreInput">
      <input type="hidden" name="quiz_id" value="<?= $quizId ?>">
      <input type="hidden" name="results" id="resultsInput">
    </form>
  </div>

<script>
const questions = <?= json_encode($questionArray) ?>;
const qArea = document.getElementById('question-area');
const submitBtn = document.getElementById('submitBtn');
const nextBtn = document.getElementById('nextBtn');
const explanationBox = document.getElementById('explanation');
const scoreInput = document.getElementById('finalScoreInput');
const resultsInput = document.getElementById('resultsInput');
const scoreForm = document.getElementById('finalScoreForm');

let current = 0;
let score = 0;
const results = [];

function loadQuestion(index) {
  const q = questions[index];
  qArea.innerHTML = `
    <h3 class="question-title">Question ${q.QUESTION_NO}: ${q.QUESTION_CONTENT}</h3>
    <div class="options-container">
      <div class="option" data-opt="A">${q.QUESTION_OPTION1}</div>
      <div class="option" data-opt="B">${q.QUESTION_OPTION2}</div>
      <div class="option" data-opt="C">${q.QUESTION_OPTION3}</div>
      <div class="option" data-opt="D">${q.QUESTION_OPTION4}</div>
    </div>
  `;

  document.querySelectorAll('.option').forEach(opt => {
    opt.addEventListener('click', () => {
      document.querySelectorAll('.option').forEach(o => o.classList.remove('selected'));
      opt.classList.add('selected');
    });
  });

  submitBtn.style.display = "flex";
  nextBtn.style.display = "none";
  explanationBox.style.display = "none";
  nextBtn.querySelector('span').textContent = (index === questions.length - 1) ? "Finish Quiz" : "Next Question";
}

submitBtn.addEventListener('click', () => {
  const selected = document.querySelector('.option.selected');
  if (!selected) return alert("Please select an answer!");
  
  const userAnswer = selected.dataset.opt;
  const correct = questions[current].QUESTION_CORRECT_OPTION;
  let scoreChange = 0;
  let isCorrect = false;

  if (userAnswer === correct) {
    selected.classList.add('correct');
    scoreChange = 100;
    score += scoreChange;
    isCorrect = true;
  } else {
    selected.classList.add('wrong');
    document.querySelector(`.option[data-opt="${correct}"]`).classList.add('correct');
    scoreChange = -50;
    score = Math.max(0, score + scoreChange); // Ensure score doesn't go below 0
    isCorrect = false;
  }

  results.push({ 
    no: questions[current].QUESTION_NO, 
    correct: isCorrect,
    userAnswer: userAnswer,
    correctAnswer: correct
  });

  submitBtn.style.display = 'none';
  nextBtn.style.display = 'flex';
  
  // Show explanation and score change
  explanationBox.innerHTML = `
    <strong>Explanation:</strong> ${questions[current].QUESTION_EXPLANATION}
    <div class="score-display ${isCorrect ? 'score-positive' : 'score-negative'}">
      ${isCorrect ? '+' : ''}${scoreChange} points (Current score: ${score})
    </div>
  `;
  explanationBox.style.display = 'block';
});

nextBtn.addEventListener('click', () => {
  if (current >= questions.length - 1) {
    showFinalForm();
  } else {
    current++;
    loadQuestion(current);
  }
});

function showFinalForm() {
  scoreInput.value = score;
  resultsInput.value = JSON.stringify(results);
  fetch("store_result.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      score: score,
      quiz_id: <?= $quizId ?>,
      results: results
    })
  }).then(() => {
    window.location.href = "result_quiz.php";
  });
}

window.onload = () => {
  loadQuestion(current);
};
</script>
</body>
</html>