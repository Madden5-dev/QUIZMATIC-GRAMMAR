<?php
session_start();

// Sambungan ke pangkalan data
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quizmaticgrammar"; // ubah nama pangkalan data

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Gagal sambung ke pangkalan data: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = $_POST['email'];
    $user_type = "user";

    // Semak sama ada USER_NAME atau USER_EMAIL sudah wujud
    $check = "SELECT * FROM user WHERE USER_NAME='$username' OR USER_EMAIL='$email'";
    $result = $conn->query($check);
    if ($result->num_rows > 0) {
        echo "<script>
                alert('Username atau email sudah didaftarkan.');
                window.history.back();
              </script>";
        exit;
    }

    // Masukkan data (tanpa hash seperti diminta)
    $sql = "INSERT INTO user (USER_NAME, USER_PASSWORD, USER_EMAIL, USER_TYPE) 
            VALUES ('$username', '$password', '$email', '$user_type')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Akaun anda berjaya didaftarkan. Sila log masuk.');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "<script>
                alert('Ralat semasa pendaftaran. Sila cuba semula.');
                window.history.back();
              </script>";
    }

    $conn->close();
    exit; // pastikan HTML di bawah tidak diproses selepas POST
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign Up | Quizmatic Grammar</title>
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
  input[type="password"],
  input[type="email"] {
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

  .login-link {
    margin-top: 20px;
    text-align: center;
    color: #666;
  }

  .login-link a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
  }

  .login-link a:hover {
    text-decoration: underline;
  }

  @media (max-width: 480px) {
    .container {
      padding: 30px 20px;
    }
    
    h2 {
      font-size: 1.5rem;
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
    <h2>Sign up</h2>
    
    <form id="signupForm" action="signUp.php" method="POST" onsubmit="return validateForm()">
      <div class="form-group">
        <input type="text" placeholder="Username*" name="username" id="username" required>
      </div>
      
      <div class="form-group">
        <input type="password" placeholder="Password*" name="password" id="password" required>
      </div>
      
      <div class="form-group">
        <input type="email" placeholder="Email*" name="email" id="email" required>
      </div>

      <button type="submit">Sign up</button>
    </form>
    
    <p class="login-link">Already have an account? <a href="login.php">Log in</a></p>
  </div>

  <script src="signUp.js"></script>
</body>
</html>