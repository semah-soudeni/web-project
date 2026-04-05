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
    <link rel="stylesheet" href="../../assets/css/theatro.css">
    <title>Theatro Insat</title>
</head>

<body>
    <nav class="navigation">
        <div class="nav-container">
            <a href="../../index.php" class="back-link">← Back to Clubs</a>
            <div class="nav-menu">
                <a href="../../index.php" class="nav-link">Clubs</a>
                <a href="../events.php" class="nav-link">Events</a>
                <a href="../map.php" class="nav-link">Map</a>
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

    <div class="curtain">
        <div class="curtain-left"></div>
        <div class="curtain-right"></div>
    </div>

    <section class="header-section">
        <div class="header-container">
            <div class="logo-container">
                <img src="../../assets/img/theatro.jpg" alt="Theatro Logo" class="theatro-logo">
            </div>
            <h1 class="header-title">Theatro INSAT</h1>
            <p class="header-subtitle">Where passion meets the stage. Discover the art of acting, directing, and
                theatrical expression.</p>
        </div>
    </section>

    <div class="main-content">
        <section class="About fade-in">
            <h2 class="section-title">Act I: Who We Are</h2>
            <div class="about-grid">
                <div class="about-card">
                    <h3>🎭 The Stage is Ours</h3>
                    <p>Theatro INSAT is the premier drama club of our institute. We are a family of actors, writers, and
                        artists dedicated to the performing arts.</p>
                </div>
                <div class="about-card">
                    <h3>✨ Creativity Unbound</h3>
                    <p>Whether it's classic plays, modern drama, or improvisational comedy, we explore all facets of
                        theatrical expression to entertain and provoke thought.</p>
                </div>
                <div class="about-card">
                    <h3>🤝 A Welcoming Troupe</h3>
                    <p>No prior acting experience required! If you have a passion for the stage, we have a role for
                        you—be it under the spotlight or behind the scenes.</p>
                </div>
            </div>
        </section>

        <section class="Activities fade-in">
            <h2 class="section-title">Act II: What We Do</h2>
            <div class="activities-flex">
                <div class="activity-box">
                    <h3>Workshops</h3>
                    <p>Weekly sessions focusing on voice acting, body language, improvisation, and scriptwriting.</p>
                </div>
                <div class="activity-box main-stage">
                    <h3>The Annual Play</h3>
                    <p>Our grand masterpiece! Months of preparation culminate in a breathtaking performance for the
                        entire campus.</p>
                </div>
                <div class="activity-box">
                    <h3>Improv Nights</h3>
                    <p>Spontaneous, hilarious, and completely unscripted. Join our improv nights for continuous laughs.
                    </p>
                </div>
            </div>
        </section>

        <section class="Achievements fade-in">
            <h2 class="section-title">Act II.V: Achievements</h2>
            <div class="stats-container">
                <div class="stat-box">
                    <h3>50+</h3>
                    <p>Active Actors</p>
                </div>
                <div class="stat-box">
                    <h3>10+</h3>
                    <p>Annual Plays</p>
                </div>
                <div class="stat-box">
                    <h3>100+</h3>
                    <p>Workshops</p>
                </div>
            </div>
        </section>

        <section class="Community fade-in">
            <h2 class="section-title">Act III: The Cast</h2>
            <div class="board-grid">
                <div class="board-member">
                    <div class="member-avatar">🎭</div>
                    <h4>Jane Doe</h4>
                    <p>President</p>
                </div>
                <div class="board-member">
                    <div class="member-avatar">🎬</div>
                    <h4>John Smith</h4>
                    <p>Vice President</p>
                </div>
                <div class="board-member">
                    <div class="member-avatar">📝</div>
                    <h4>Alice Jones</h4>
                    <p>General Secretary</p>
                </div>
            </div>
        </section>

        <section class="Join fade-in">
            <h2 class="section-title">Act III: Join The Cast</h2>
            <div class="join-container">
                <p>Are you ready for your standing ovation?</p>
                <button class="join-btn" onclick="window.joinClub('theatro')">Audition Now</button>
            </div>
        </section>
    </div>

    <script src="../../assets/js/theatro.js"></script>
    <script src="../../assets/js/auth.js"></script>
</body>

</html>




