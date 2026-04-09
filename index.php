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
    <link rel="stylesheet" href="assets/css/style.css">
    <title>INSAT Clubs</title>
</head>
<body>
    <main>
        <nav class="navigation">
            <div class="nav-container">
                <div class="nav-menu">
                    <a href="index.html" class="nav-link">Clubs</a>
                    <a href="pages/events.html" class="nav-link">Events</a>
                    <a href="pages/map.html" class="nav-link">Map</a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="pages/admin.html" class="nav-link">Admin Dashboard</a>
                    <?php endif; ?>
                </div>
                <div class="nav-login">
                   <?php if ($isLoggedIn): ?>
                    <span>Hi, <?php echo htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?></span>
                    <form action="backend/logout.php" method="POST" style="display:inline;">
                        <button type="submit" class="signout-btn">Sign Out</button>
                    </form>
                    <?php else: ?>
                    <a href="pages/signin.php" class="signin-btn">Sign In</a>
                    <a href="pages/signup.php" class="signup-btn">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <section>
            <h1>Discover our clubs</h1>
            <div class="container">
                <a href="pages/clubs/aero.php" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logo">
                                <img src="assets/img/aero-logo.png" alt="aerobotix logo">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>Aerobotix</h2>
                            <p>Learn.Create.Innovate</p>
                        </div>
                    </div>
                </a>
                <a href="pages/clubs/secu.php" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logo secu">
                                <img src="assets/img/secu-logo.png" alt="securinets logo">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>Securinets</h2>
                            <p>Cybersecurity & Ethical Hacking</p>
                        </div>
                    </div>
                </a>
                <a href="pages/clubs/ieee.php" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logo">
                                <img src="assets/img/ieee-logo.png" alt="ieee logo">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>IEEE</h2>
                            <p>Advancing Technology for Humanity</p>
                        </div>
                    </div>
                </a>
                <a href="pages/clubs/acm.php" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logo">
                                <img src="assets/img/acm-logo.png" alt="acm logo">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>ACM</h2>
                            <p>Computing for Innovation</p>
                        </div>
                    </div>
                </a>
                
                <a href="pages/clubs/cim.php" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logo">
                                <img src="assets/img/cim.png" alt="CIM Insat">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>CIM </h2>
                            <p>Industrial Computing & Mechatronics</p>
                        </div>
                    </div>
                </a>
                <a href="pages/clubs/theatro.php" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logoth">
                                <img src="assets/img/theatro.jpg" alt="Theatro Insat">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>Theatro</h2>
                            <p>Unleash Your Inner Actor</p>
                        </div>
                    </div>
                </a>
                
                <a href="pages/clubs/press.php" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logoth">
                                <img src="assets/img/press.png" alt="Insat Press">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>Insat Press</h2>
                            <p>Your Voice in the Campus</p>
                        </div>
                    </div>
                </a>
                
                
                
            </div>
        </section>
    </main>
    <script src="assets/js/auth.js"></script>
</body>

</html>