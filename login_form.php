<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Log In</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
  <div class="container">
    <h2>Log In</h2>

    <form id="signupForm" action="login.php" method="POST">
      <div class="radio-group">
        <label><input type="radio" name="user_type" value="user" checked> User</label>
        <label><input type="radio" name="user_type" value="administrator"> Administrator</label>
      </div>
      <input type="text" placeholder="Username*" name="username" required>
      <input type="password" placeholder="Password*" name="password" required>

      <button type="submit">Log in</button>
    </form>

    <p class="signUp-link">Or <a href="signUp.html">Sign Up</a></p>
  </div>
</body>
</html>
