<?php
require_once __DIR__ . '/../../includes/init.php';

$pageTitle  = 'Astro Club INSAT';
$extraCss   = [BASE_URL . 'assets/css/astro.css'];
$extraJs    = [BASE_URL . 'assets/js/astro.js'];
?>

<?php require_once ROOT_PATH . '/views/header.php'; ?>

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

<?php require_once ROOT_PATH . '/views/footer.php'; ?>