<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "quizmaticgrammar");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    $sql = "SELECT * FROM user WHERE USER_NAME = '$username' AND USER_PASSWORD = '$password' AND USER_TYPE = '$user_type'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $data = $result->fetch_assoc();

        $_SESSION['user'] = $data;
        $_SESSION['username'] = $data['USER_NAME'];
        $_SESSION['user_type'] = $data['USER_TYPE'];

        if ($data['USER_TYPE'] === 'administrator') {
            header("Location: admin_homepage.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit;

    } else {
        echo "<script>alert('Invalid username, password or user type.'); window.location.href='login.php';</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Quizmatic Grammar</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="icon" type="image/png" href="logo_website.png">
  <style>
  :root {
    --primary: #00c3e3;
    --primary-dark: #00a9c7;
    --primary-light: #80e1f1;
    --white: #ffffff;
    --dark: #2b2d42;
    --light: #f8f9fa;
    --border-radius: 8px;
    --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
  }

  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--light);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
  }

  .back-btn {
    position: absolute;
    top: 20px;
    left: 20px;
  }

  .back-btn a {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: var(--dark);
    font-weight: 500;
    transition: var(--transition);
  }

  .back-btn a:hover {
    color: var(--primary);
  }

  .back-btn svg {
    width: 18px;
    height: 18px;
    margin-right: 5px;
  }

  .container {
    background-color: var(--white);
    padding: 40px;
    border-radius: var(--border-radius);
    width: 100%;
    max-width: 420px;
    box-shadow: var(--shadow);
    position: relative;
    overflow: hidden;
  }

  .container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
    background: linear-gradient(to bottom, var(--primary), var(--primary-dark));
  }

  h2 {
    margin-bottom: 25px;
    color: var(--dark);
    font-size: 1.8rem;
    text-align: center;
  }

  .form-group {
    margin-bottom: 20px;
    position: relative;
  }

  input[type="text"],
  input[type="password"] {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid #e0e0e0;
    border-radius: var(--border-radius);
    font-size: 15px;
    transition: var(--transition);
    background-color: var(--light);
  }

  input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(0, 195, 227, 0.2);
  }

  .radio-group {
    margin: 20px 0;
    display: flex;
    justify-content: center;
    gap: 20px;
  }

  .radio-group label {
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    font-size: 14px;
    color: var(--dark);
    transition: var(--transition);
  }

  .radio-group label:hover {
    color: var(--primary);
  }

  input[type="radio"] {
    accent-color: var(--primary);
    width: 16px;
    height: 16px;
    cursor: pointer;
  }

  button[type="submit"] {
    width: 100%;
    padding: 14px;
    background-color: var(--primary);
    color: var(--white);
    font-size: 16px;
    font-weight: 600;
    border: none;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
    margin-top: 10px;
  }

  button[type="submit"]:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
  }

  .signUp-link {
    margin-top: 20px;
    text-align: center;
    color: #666;
  }

  .signUp-link a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
  }

  .signUp-link a:hover {
    text-decoration: underline;
  }

  @media (max-width: 480px) {
    .container {
      padding: 30px 20px;
    }
    
    h2 {
      font-size: 1.5rem;
    }
    
    .radio-group {
      flex-direction: column;
      gap: 10px;
      align-items: center;
    }
  }
  </style>
</head>
<body>
  <div class="back-btn">
    <a href="homepage.php">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M19 12H5M12 19l-7-7 7-7"></path>
      </svg>
      Back
    </a>
  </div>

  <div class="container">
    <h2>Log In</h2>
    
    <form id="signupForm" action="login.php" method="POST">
      <div class="radio-group">
        <label><input type="radio" name="user_type" value="user" checked> User</label>
        <label><input type="radio" name="user_type" value="administrator"> Administrator</label>
      </div>
      
      <div class="form-group">
        <input type="text" placeholder="Username*" name="username" required>
      </div>
      
      <div class="form-group">
        <input type="password" placeholder="Password*" name="password" required>
      </div>

      <button type="submit">Log in</button>
    </form>
    
    <p class="signUp-link">Don't have an account? <a href="signUp.php">Sign Up</a></p>
  </div>
</body>
</html>