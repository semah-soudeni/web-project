<?php
session_start();

$isLoggedIn = isset($_SESSION['logged']) && $_SESSION['logged'] === 'yes';
$displayName = '';

if ($isLoggedIn) {
  $firstName = trim((string)($_SESSION['user_first_name'] ?? ''));
  $lastName = trim((string)($_SESSION['user_last_name'] ?? ''));
  $displayName = trim($firstName . ' ' . $lastName);

  if ($displayName === '') {
    $displayName = (string)($_SESSION['user_email'] ?? 'User');
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/map.css">
    <title>Upcoming Events</title>
</head>
<body>
    <nav class="navigation">
        <div class="nav-container">
            <a href="../index.php" class="back-link">←Back to Clubs</a>
            <div class="nav-menu">
                <a href="/index.php" class="nav-link">Clubs</a>
                <a href="/pages/events.php" class="nav-link">Events</a>
                <a href="/pages/map.php" class="nav-link active">Map</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="pages/admin.php" class="nav-link">Admin Dashboard</a>
                <?php endif; ?>
            </div>
            <div class="nav-login">
                 <?php if ($isLoggedIn): ?>
                    <form action="backend/logout.php" method="POST" style="display:flex; align-items:center; gap:20px;">
                        <span id="nav-user-name" style="font-weight:600;">
                            <?php echo "Hi, " .htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <button id="nav-logout-btn" class="signin-btn">Sign Out</button>
                        </form>
                        <?php else: ?>
                    <a href="./signin.php" class="signin-btn">Sign In</a>
                    <a href="./signup.php" class="signup-btn">Sign Up</a>
                    <?php endif; ?>
            </div>
        </div>
        </div>
    </nav>

    <section class="header-section">
        <div class="header-container">
            <h1 class="header-title">This is the map of the campus</h1>
            <p class="header-subtitle">Click any of the marks to discover the club there.</p>
        </div>
    </section>

    <div class="main">
      <div onclick="openBar('aero')" style="position: absolute;top: 10%;left: 22%;width: 50px">
        <svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="#000000">
          <circle r="400" cx="450" cy="-410" fill="white" />
          <circle r="350" cx="450" cy="-410" fill="#123caa" />
        </svg>
      </div>
      <div onclick="openBar('ieee')" style="position: absolute;top: 10%;left: 27%;width: 50px">
        <svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="#000000">
          <circle r="400" cx="450" cy="-410" fill="white" />
          <circle r="350" cx="450" cy="-410" fill="purple" />
        </svg>
      </div>
      <div onclick="openBar('secu')" style="position: absolute;top: 19%;left: 59%;width: 50px">
        <svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="#000000">
          <circle r="400" cx="450" cy="-410" fill="white" />
          <circle r="350" cx="450" cy="-410" fill="#e73c37" />
        </svg>
      </div>
      <div onclick="openBar('acm')" style="position: absolute;top: 19%;left: 64%;width: 50px">
        <svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="#000000">
          <circle r="400" cx="450" cy="-410" fill="white" />
          <circle r="350" cx="450" cy="-410" fill="blue" />
        </svg>
      </div>


      <div class="sidebar" id="sidebar">
        <button onclick="closeBar()"> --> </button>
        <p id="test"></p>
      </div>
    </div>
    <script src="/assets/js/map.js"></script>
</body>
</html>
