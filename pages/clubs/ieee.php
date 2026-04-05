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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
        <link rel="stylesheet" href="/assets/css/ieee.css">
    </head>
    <body>
        <nav class="navigation">
            <div class="nav-container">
                <a href="/index.html" class="back-link">←Back to Clubs</a>
                <div class="nav-menu">
                    <a href="/index.html" class="nav-link">Clubs</a>
                    <a href="/pages/events.html" class="nav-link">Events</a>
                    <a href="/pages/under-construction.html" class="nav-link">Map</a>
                </div>
                <div class="nav-login">
                    <?php if ($isLoggedIn): ?>
                    <span class="signin-btn" style="cursor: default;">Hi, <?php echo htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php else: ?>
                    <a href="../signin.php" class="signin-btn">Sign In</a>
                    <a href="../signup.php" class="signup-btn">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <!--<div class="img-container"></div>-->
        <canvas id="rockets"></canvas>
        <div></div>
<div></div>
<div></div>
        <script src="/assets/js/ieee.js" defer></script>
    </body>
</html>
