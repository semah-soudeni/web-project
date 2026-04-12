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
    <title>Astro Club INSAT</title>
    <link rel="stylesheet" href="/assets/css/astro.css">
</head>

<body>
    <nav class="navigation">
        <div class="nav-container">
            <a href="/index.php" class="back-link">← Back to Clubs</a>
            <div class="nav-menu">
                <a href="/index.php" class="nav-link">Clubs</a>
                <a href="/pages/events.php" class="nav-link">Events</a>
                <a href="/pages/map.php" class="nav-link">Map</a>
            </div>
            <div class="nav-login">
                <?php if ($isLoggedIn): ?>
                <span style="cursor: default;">Hi, <?php echo htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?></span>
                <?php else: ?>
                <a href="/pages/signin.php" class="signin-btn">Sign In</a>
                <a href="/pages/signup.php" class="signup-btn">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Universe Background Container -->
    <div id="universe-canvas"></div>

    <header class="astro-hero">
        <div class="glass-container">
            <div class="astro-logo-container">
                <img src="/assets/img/astro.png" alt="Astro Club Logo" class="astro-logo">
            </div>
            <h1>Astro Club <span>INSAT</span></h1>
            <p class="subtitle">Reach for the Stars, Explore the Cosmos.</p>
            <button class="astro-btn" onclick="window.joinClub('astro')">Launch Your Journey</button>
        </div>
    </header>

    <main class="astro-main">
        <section class="astro-about text-center">
            <h2 class="section-title">About Our Universe</h2>
            <p>Astro Club INSAT is a community of space enthusiasts, amateur astronomers, and rocket hobbyists. We aim
                to demystify the cosmos through regular stargazing nights, educational workshops, and hands-on rocketry
                projects, proving that the sky is never the limit.</p>
        </section>

        <section class="missions-section">
            <h2 class="section-title">Our Missions</h2>
            <div class="mission-cards">
                <div class="mission">
                    <div class="icon">🔭</div>
                    <h3>Stargazing</h3>
                    <p>Nighttime observation sessions using professional telescopes to map constellations and planets.
                    </p>
                </div>
                <div class="mission">
                    <div class="icon">🚀</div>
                    <h3>Rocketry 101</h3>
                    <p>Designing, building, and launching model rockets. Learn the physics of escaping Earth's gravity.
                    </p>
                </div>
                <div class="mission">
                    <div class="icon">🌌</div>
                    <h3>Astrophysics</h3>
                    <p>Workshops to decode the mysteries of black holes, dark matter, and the origins of the universe.
                    </p>
                </div>
            </div>
        </section>

        <section class="astro-achievements text-center">
            <h2 class="section-title">Mission Logs</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>50+</h3>
                    <p>Observation Nights</p>
                </div>
                <div class="stat-card">
                    <h3>10+</h3>
                    <p>Rockets Launched</p>
                </div>
                <div class="stat-card">
                    <h3>200+</h3>
                    <p>Stargazers</p>
                </div>
            </div>
        </section>

        <section class="command-board">
            <h2 class="section-title text-center">Mission Command</h2>
            <div class="board-grid">
                <div class="board-member">
                    <div class="avatar">👨‍🚀</div>
                    <h3>Neil Armstrong</h3>
                    <p>Commander (President)</p>
                </div>
                <div class="board-member">
                    <div class="avatar">📡</div>
                    <h3>Sally Ride</h3>
                    <p>Comms Officer (VP)</p>
                </div>
                <div class="board-member">
                    <div class="avatar">🛰️</div>
                    <h3>Carl Sagan</h3>
                    <p>Science Lead</p>
                </div>
            </div>
        </section>

        <section class="astro-membership text-center">
            <h2 class="section-title">Join The Crew</h2>
            <p>Ready to embark on an interstellar journey? Become a member to access our telescopes and join the rocket
                teams.</p>
            <button class="astro-btn" onclick="window.joinClub('astro')">Apply For Liftoff</button>
        </section>
    </main>

    <footer class="astro-footer">
        <p>Astro Club INSAT &copy; 2024. Ad Astra.</p>
    </footer>

    <script src="/assets/js/astro.js"></script>
    <script src="/assets/js/auth.js"></script>
</body>

</html>




