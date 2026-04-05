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
    <title>CIM - Club INSAT Maintenance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/cim.css">
</head>

<body>

<!-- ===============================
     NAVIGATION
================================= -->

<nav class="navigation">
    <div class="nav-container">
        <a href="/index.html" class="back-link">← Back to Clubs</a>

        <div class="nav-menu">
            <a href="/index.html" class="nav-link">Clubs</a>
            <a href="/pages/events.html" class="nav-link">Events</a>
            <a href="/pages/map.html" class="nav-link">Map</a>
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

<!-- ===============================
     HEADER
================================= -->

<header class="header-section">
    <div class="header-content">
        <h1>Club INSAT Maintenance</h1>
        <p>Building • Repairing • Improving Our Campus</p>
    </div>
</header>

<!-- ===============================
     MAIN CONTENT
================================= -->

<main>

<section class="about-section">
    <h2>About CIM</h2>
    <p>
        CIM is the engineering backbone of INSAT.  
        We maintain infrastructure, repair technical systems,
        and lead construction and improvement projects across campus.
    </p>
</section>

<section class="activities-section">
    <h2>What We Do</h2>

    <div class="cards">

        <div class="card">
            <h3>🔧 Maintenance</h3>
            <p>Repairing classrooms, labs and essential equipment.</p>
        </div>

        <div class="card">
            <h3>🚜 Construction</h3>
            <p>Building and upgrading campus facilities.</p>
        </div>

        <div class="card">
            <h3>⚙️ Technical Support</h3>
            <p>Providing logistics and technical assistance to clubs and departments.</p>
        </div>

    </div>
</section>

<section class="impact-section">
    <h2>Our Impact</h2>

    <div class="stats">

        <div class="stat">
            <h1>50+</h1>
            <p>Projects Completed</p>
        </div>

        <div class="stat">
            <h1>100+</h1>
            <p>Repairs Done</p>
        </div>

        <div class="stat">
            <h1>20+</h1>
            <p>Active Members</p>
        </div>

    </div>
</section>

<section class="join-section">
    <h2 style="border-left: none;">Join CIM</h2>
    <p>Be part of the team that builds INSAT’s future.</p>
    <button class="join-btn">Join Now</button>
</section>

</main>

<!-- ===============================
     INDUSTRIAL FOOTER
================================= -->

<footer class="industrial-footer">

    <div class="footer-content">
        <h2>CIM - Club INSAT Maintenance</h2>
        <p>Engineering the Campus Since Day One</p>
    </div>

    <canvas id="construction-canvas"></canvas>

</footer>

<script src="/assets/js/cim.js"></script>

</body>
</html>
