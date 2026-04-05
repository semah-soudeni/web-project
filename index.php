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
                    <a href="index.php" class="nav-link active">Clubs</a>
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
                <a href="pages/clubs/aero.html" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logo">
                                <img src="/assets/img/aero-logo.png" alt="aerobotix logo">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>Aerobotix</h2>
                            <p>Learn.Create.Innovate</p>
                        </div>
                    </div>
                </a>
                <a href="pages/clubs/secu.html" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logo secu">
                                <img src="/assets/img/secu-logo.png" alt="securinets logo">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>Securinets</h2>
                            <p>Innovate.Code.Transform</p>
                        </div>
                    </div>
                </a>
                <a href="pages/clubs/ieee.html" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logo">
                                <img src="/assets/img/ieee-logo.png" alt="ieee logo">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>IEEE</h2>
                            <p>Innovate.Code.Transform</p>
                        </div>
                    </div>
                </a>
                <a href="pages/clubs/acmtest.html" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logo">
                                <img src="/assets/img/acm-logo.png" alt="acm logo">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>ACM</h2>
                            <p>Innovate.Code.Transform</p>
                        </div>
                    </div>
                </a>
                <a href="pages/under-construction.html" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logoAnd">
                                <img src="/assets/img/andr.png" alt="Insat Android Club Logo">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>Android Club </h2>
                            <p>Innovate.Code.Transform</p>
                        </div>
                    </div>
                </a>
                <a href="pages/clubs/cim.html" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logo">
                                <img src="/assets/img/cim.png" alt="CIM Insat">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>CIM </h2>
                            <p>Innovate.Code.Transform</p>
                        </div>
                    </div>
                </a>
                <a href="pages/under-construction.html" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logoth">
                                <img src="/assets/img/theatro.jpg" alt="CIM Insat">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>Theatro</h2>
                            <p>Innovate.Code.Transform</p>
                        </div>
                    </div>
                </a>
                <a href="pages/under-construction.html" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logoth">
                                <img src="/assets/img/cine.jpg" alt="CIM Insat">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>Club Cine Radio</h2>
                            <p>Innovate.Code.Transform</p>
                        </div>
                    </div>
                </a>
                <a href="pages/under-construction.html" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logoth">
                                <img src="/assets/img/press.png" alt="CIM Insat">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>Insat Press</h2>
                            <p>Innovate.Code.Transform</p>
                        </div>
                    </div>
                </a>
                <a href="pages/under-construction.html" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logoth">
                                <img src="/assets/img/lion.jpg" alt="CIM Insat">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>Lions Club</h2>
                            <p>Innovate.Code.Transform</p>
                        </div>
                    </div>
                </a>
                <a href="pages/under-construction.html" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logoAnd">
                                <img src="/assets/img/enactus.png" alt="CIM Insat">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>Club Enactus </h2>
                            <p>Innovate.Code.Transform</p>
                        </div>
                    </div>
                </a>
                <a href="pages/under-construction.html" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logoth">
                                <img src="/assets/img/junior.jpg" alt="CIM Insat">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2> Club JEI </h2>
                            <p>Innovate.Code.Transform</p>
                        </div>
                    </div>
                </a>
                <a href="pages/under-construction.html" class="link">
                    <div class="card">
                        <div class="img">
                            <div class="logoth">
                                <img src="/assets/img/juniorc.jpg" alt="CIM Insat">
                            </div>
                        </div>
                        <div class="jnab">
                            <h2>Club JCI</h2>
                            <p>Innovate.Code.Transform</p>
                        </div>
                    </div>
                </a>
            </div>
        </section>
    </main>
</body>
</html>
