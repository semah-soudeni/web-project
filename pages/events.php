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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/events.css">
    <title>Upcoming Events</title>
</head>

<body>
    <nav class="navigation">
        <div class="nav-container">
            <div class="nav-menu">
                <a href="/index.php" class="nav-link">Clubs</a>
                <a href="/pages/events.php" class="nav-link">Events</a>
                <a href="/pages/map.php" class="nav-link">Map</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="/pages/admin.php" class="nav-link">Admin Dashboard</a>
                <?php endif; ?>
            </div>
            <div class="nav-login">
                 <?php if ($isLoggedIn): ?>
                    <form action="/backend/logout.php" method="POST" style="display:flex; align-items:center; gap:20px;">
                        <span id="nav-user-name" style="font-weight:600;">
                            <?php echo "Hi, " .htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <button id="nav-logout-btn" class="signin-btn">Sign Out</button>
                        </form>
                        <?php else: ?>
                    <a href="/pages/signin.php" class="signin-btn">Sign In</a>
                    <a href="/pages/signup.php" class="signup-btn">Sign Up</a>
                    <?php endif; ?>
            </div>
        </div>
    </nav>

    <section class="header-section">
        <div class="header-container">
            <h1 class="header-title">Upcoming Events</h1>
            <p class="header-subtitle">Discover and participate in exciting events from all our clubs</p>
        </div>
    </section>

    <section class="filter-section">
        <div class="container">
            <div class="filters">
                <button class="filter-btn active" data-club="all">All Clubs</button>
                <button class="filter-btn" data-club="aero">Aerobotix</button>
                <button class="filter-btn" data-club="secu">Securinets</button>
                <button class="filter-btn" data-club="ieee">IEEE</button>
                <button class="filter-btn" data-club="acm">ACM</button>
                <button class="filter-btn" data-club="android">Android Club</button>
                <button class="filter-btn" data-club="cim">CIM</button>
            </div>
        </div>
    </section>
    <section class="event-section">
        <div class="container">
            <div id="events-container"></div>
        </div>
        <div id="loader" class="loader">
            <img src="/assets/img/load.gif"/>
        </div>
    </section>
    <script src="/assets/js/events.js"></script>
</body>

</html>
