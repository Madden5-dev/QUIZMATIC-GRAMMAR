<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/png" href="logo_website.png">
    <title>Create Quiz</title>
    <style>
        /* Main Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        /* Main Container */
        .main-container {
            display: flex;
            justify-content: center;
            padding: 10px;
        }

        /* Form Container */
        .form-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 700px;
            padding: 30px;
        }

        /* Form Header */
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-title {
            color: #2c3e50;
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
            font-size: 16px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
        }

        .form-select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
        }

        .form-hint {
            font-size: 13px;
            color: #7f8c8d;
            margin-top: 6px;
        }

        /* Button Groups */
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background-color: #00bcd4;
            color: white;
        }

        .btn-secondary {
            background-color: #00bcd4;
            color: white;
        }

	.btn-primary:hover {
            background-color: #14A3C7;
        }

        .btn-secondary:hover {
            background-color: #14A3C7;
        }	

	#quiz_title {
		width: 95.8%;
	}
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="main-container">
    <div class="form-container">
        <div class="form-header">
            <h1 class="form-title">Create a New Quiz</h1>
        </div>

        <form action="save_quiz.php" method="POST">
            <div class="form-group">
                <label class="form-label" for="quiz_title">Quiz Title</label>
                <input class="form-input" type="text" id="quiz_title" name="quiz_title" placeholder="e.g. Advanced Vocabulary" required>
                <p class="form-hint" style="font-style: italic;">Choose a clear, descriptive title for your quiz.</p>
            </div>

            <div class="form-group">
                <label class="form-label" for="quiz_category">Category</label>
                <select class="form-select" id="quiz_category" name="quiz_category" required>
                    <option value="Verb Tenses" selected>Verb Tenses</option>
			<option value="Phrasal Verbs">Phrasal Verbs</option>
			<option value="Prepositions">Prepositions</option>
			<option value="Other Grammar Topics">Other Grammar Topics</option>
	
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="quiz_difficulty">Difficulty Level</label>
                <select class="form-select" id="quiz_difficulty" name="quiz_difficulty" required>
                    <option value="Easy" selected>Easy</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Hard">Hard</option>
                </select>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-secondary"  onclick="window.location.href='quiz_homepage.php'">Back</button>
                <button type="submit" name="create_quiz" class="btn btn-primary">Continue to add question</button>
            </div>
        </form>
    </div>
</div>

<script>
    function goBack() {
        window.history.back();
    }
</script>

<?php include 'logout_modal.php'; ?>

</body>
</html>


