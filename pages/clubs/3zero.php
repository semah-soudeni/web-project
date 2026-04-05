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
    <title>3Zero Campus Club INSAT</title>
    <link rel="stylesheet" href="../../assets/css/3zero.css">
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

    <!-- Leaf animation canvas container -->
    <div id="eco-canvas"></div>

    <header class="eco-hero">
        <div class="hero-box">
            <div class="logo-wrapper">
                <img src="../../assets/img/3zero.png" alt="3Zero Logo" class="eco-logo">
            </div>
            <h1>3Zero <span>Campus Club</span></h1>
            <h2>Zero Exclusion | Zero Carbon | Zero Poverty</h2>
            <p>Empowering the campus community towards a fully sustainable future.</p>
            <button class="eco-btn" onclick="window.joinClub('3zero')">Join The Movement</button>
        </div>
    </header>

    <main class="eco-main">
        <section class="eco-about text-center">
            <h2 class="section-title">About Our Club</h2>
            <p>3Zero Campus Club INSAT is a student-led initiative dedicated to environmental sustainability and social
                equality. Driven by a passion for creating a greener, fairer world, we work on campus-wide recycling
                projects, charity drives, and energy-saving campaigns.</p>
        </section>

        <section class="pillars-section">
            <h2 class="section-title">Our Three Pillars</h2>
            <div class="pillar-grid">
                <div class="eco-card">
                    <div class="icon">🤝</div>
                    <h3>Zero Exclusion</h3>
                    <p>Fostering an inclusive environment where every voice is heard and valued, breaking down social
                        barriers.</p>
                </div>
                <div class="eco-card">
                    <div class="icon">🌱</div>
                    <h3>Zero Carbon</h3>
                    <p>Driving initiatives to reduce the campus carbon footprint, promoting recycling and renewable
                        energy awareness.</p>
                </div>
                <div class="eco-card">
                    <div class="icon">⚖️</div>
                    <h3>Zero Poverty</h3>
                    <p>Collaborating on projects that tackle economic inequalities and provide support to vulnerable
                        communities.</p>
                </div>
            </div>
        </section>

        <!-- New Certificates Section requested by user -->
        <section class="certificates-section">
            <h2 class="section-title">Certifications & Standards</h2>
            <p class="cert-intro">As advocates for structured environmental and quality management, our training aligns
                with globally recognized standards:</p>

            <div class="cert-grid">
                <div class="cert-card">
                    <div class="cert-icon">🏅</div>
                    <h4>ISO 9001</h4>
                    <p>Quality Management Systems</p>
                </div>
                <div class="cert-card">
                    <div class="cert-icon">🌍</div>
                    <h4>ISO 14001</h4>
                    <p>Environmental Management Systems</p>
                </div>
                <div class="cert-card">
                    <div class="cert-icon">👷</div>
                    <h4>ISO 45001</h4>
                    <p>Occupational Health & Safety</p>
                </div>
                <div class="cert-card">
                    <div class="cert-icon">📋</div>
                    <h4>ISO 19011</h4>
                    <p>Auditing Management Systems</p>
                </div>
            </div>
        </section>

        <section class="eco-achievements text-center">
            <h2 class="section-title">Our Milestone Impacts</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>500kg</h3>
                    <p>Waste Recycled</p>
                </div>
                <div class="stat-card">
                    <h3>10+</h3>
                    <p>Community Drives</p>
                </div>
                <div class="stat-card">
                    <h3>200+</h3>
                    <p>Volunteers</p>
                </div>
            </div>
        </section>

        <section class="board-section">
            <h2 class="section-title text-center">The Green Committee</h2>
            <div class="board-grid">
                <div class="board-member">
                    <div class="avatar">👨‍🌾</div>
                    <h3>Emma Green</h3>
                    <p>President</p>
                </div>
                <div class="board-member">
                    <div class="avatar">👩‍💼</div>
                    <h3>Lucas Soil</h3>
                    <p>VP Sustainability</p>
                </div>
                <div class="board-member">
                    <div class="avatar">🧑‍🏫</div>
                    <h3>Maya Rivers</h3>
                    <p>Inclusion Lead</p>
                </div>
            </div>
        </section>

        <section class="eco-membership text-center">
            <h2 class="section-title">Be The Change</h2>
            <p>Want to leave a positive footprint? Join us to collaborate on green tech projects and community
                outreaches.</p>
            <button class="eco-btn" onclick="window.joinClub('3zero')">Volunteer Now</button>
        </section>
    </main>

    <footer class="eco-footer">
        <p>3Zero Campus Club INSAT &copy; 2024. For a Sustainable Tomorrow.</p>
    </footer>

    <script src="../../assets/js/3zero.js"></script>
    <script src="../../assets/js/auth.js"></script>
</body>

</html>




