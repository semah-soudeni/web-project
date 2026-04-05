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
    <title>Chemistry Club INSAT</title>
    <link rel="stylesheet" href="../../assets/css/chem.css">
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

    <div class="chem-background"></div>

    <header class="chem-hero">
        <div class="hero-content">
            <div class="flask-container">
                <img src="../../assets/img/chem.png" alt="Chem Club Logo" class="chem-logo">
            </div>
            <h1>Chemistry Club <span>INSAT</span></h1>
            <p class="subtitle">La Chimie, Secret de la Magie !</p>
            <button class="chem-btn" onclick="window.joinClub('chem')">Mix With Us</button>
        </div>
    </header>

    <main class="chem-main">
        <section class="chem-about text-center">
            <h2 class="section-title">About Our Lab</h2>
            <p>Chem Club INSAT brings together students passionate about chemistry and its practical applications. We
                organize interactive experiments, factory visits, scientific conferences, and environmental awareness
                campaigns to highlight the omnipresence of chemistry in driving modern innovation.</p>
        </section>

        <section class="formula-section">
            <h2 class="section-title">Our Element</h2>
            <div class="cards-grid">
                <div class="chem-card">
                    <div class="icon">⚗️</div>
                    <h3>Experiments</h3>
                    <p>Hands-on lab sessions exploring fascinating chemical reactions and synthesizing new compounds.
                    </p>
                </div>
                <div class="chem-card">
                    <div class="icon">🔬</div>
                    <h3>Research</h3>
                    <p>Delving into advanced topics like organic synthesis, materials science, and biochemistry.</p>
                </div>
                <div class="chem-card">
                    <div class="icon">🌍</div>
                    <h3>Green Chemistry</h3>
                    <p>Promoting sustainable practices and eco-friendly chemical processes for a better future.</p>
                </div>
            </div>
        </section>

        <section class="chem-achievements text-center">
            <h2 class="section-title">Our Formulations</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>20+</h3>
                    <p>Annual Experiments</p>
                </div>
                <div class="stat-card">
                    <h3>3</h3>
                    <p>National Awards</p>
                </div>
                <div class="stat-card">
                    <h3>150+</h3>
                    <p>Active Chemists</p>
                </div>
            </div>
        </section>

        <section class="board-section">
            <h2 class="section-title text-center">The Lab Directors</h2>
            <div class="board-grid">
                <div class="board-member">
                    <div class="avatar">👨‍🔬</div>
                    <h3>Walter White</h3>
                    <p>Head Researcher</p>
                </div>
                <div class="board-member">
                    <div class="avatar">👩‍🔬</div>
                    <h3>Marie Curie</h3>
                    <p>Lead Analyst</p>
                </div>
                <div class="board-member">
                    <div class="avatar">🧑‍🔬</div>
                    <h3>Jesse Pinkman</h3>
                    <p>Lab Assistant</p>
                </div>
            </div>
        </section>

        <section class="chem-membership text-center">
            <h2 class="section-title">Become a Catalyst</h2>
            <p>Ready to ignite a reaction? Join us today and gain access to our weekly labs and networking events.</p>
            <button class="chem-btn" onclick="window.joinClub('chem')">Apply Now</button>
        </section>
    </main>

    <footer class="chem-footer">
        <p>Chemistry Club INSAT &copy; 2024. Catalyzing Minds.</p>
    </footer>

    <script src="../../assets/js/chem.js"></script>
    <script src="../../assets/js/auth.js"></script>
</body>

</html>




