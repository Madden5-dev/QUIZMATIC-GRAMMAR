<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user']);
?>

<style>
:root {
  --primary-color: #4361ee;
  --primary-hover: #3a56d4;
  --secondary-color: #7209b7;
  --text-color: #2b2d42;
  --light-bg: #f8f9fa;
  --white: #ffffff;
  --transition: all 0.3s ease;
}

header {
  background-color: var(--white);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 5%;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  position: sticky;
  top: 0;
  z-index: 1000;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

header img.logo {
  width: 120px;
  height: auto;
  transition: var(--transition);
}

header img.logo:hover {
  transform: scale(1.05);
}

.center-nav {
  position: absolute;
  left: 0;
  right: 0;
  display: flex;
  justify-content: center;
  pointer-events: none;
}

nav {
  display: flex;
  background: var(--white);
  padding: 8px 24px;
  border-radius: 50px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

nav a {
  margin: 0 20px;
  text-decoration: none;
  color: var(--text-color);
  font-weight: 600;
  font-size: 16px;
  pointer-events: auto;
  position: relative;
  padding: 8px 0;
  transition: var(--transition);
}

nav a::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 3px;
  background: var(--secondary-color);
  transition: var(--transition);
  border-radius: 3px;
}

nav a:hover::after {
  width: 100%;
}

nav a:hover {
  color: var(--secondary-color);
}

.right-section {
  display: flex;
  align-items: center;
  gap: 12px;
}

.nav-button {
  background-color: var(--primary-color);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  box-shadow: 0 2px 8px rgba(67, 97, 238, 0.3);
}

.nav-button:hover {
  background-color: var(--primary-hover);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(67, 97, 238, 0.4);
}

.nav-button:active {
  transform: translateY(0);
}

@media (max-width: 768px) {
  header {
    flex-direction: column;
    padding: 15px 20px;
  }
  
  .center-nav {
    position: static;
    margin: 15px 0;
  }
  
  nav {
    padding: 6px 16px;
  }
  
  nav a {
    margin: 0 12px;
    font-size: 14px;
  }
  
  .right-section {
    margin-top: 10px;
  }
}
</style>

<header>
  <img src="logo.jpeg" alt="Quizmatic Grammar Logo" class="logo" />
  
  <div class="center-nav">
    <nav>
      <a href="home_redirect.php" class="nav-item">Home</a>
      <a href="quizzes_redirect.php" class="nav-item">Quizzes</a>
    </nav>
  </div>
  
  <div class="right-section">
    <?php if ($isLoggedIn): ?>
      <button class="nav-button" onclick="openLogoutModal()">Logout</button>
    <?php else: ?>
      <button class="nav-button" onclick="window.location.href='signup.php'">Sign Up</button>
      <button class="nav-button" onclick="window.location.href='login.php'">Login</button>
    <?php endif; ?>
  </div>
</header>