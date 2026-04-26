<?php
require_once __DIR__ . '/includes/init.php';

$pageTitle = 'INSAT clubs';
$activePage = 'clubs';
?>

<?php require_once ROOT_PATH . '/views/header.php'; ?>

<section>
    <h1>Discover our clubs</h1>
    <div class="container clubs-grid">
        <a href="<?= BASE_URL ?>pages/clubs/aero.php" class="link">
            <div class="card">
                <div class="img">
                    <div class="logo">
                        <img src="<?= BASE_URL ?>assets/img/aero-logo.png" alt="aerobotix logo">
                    </div>
                </div>
                <div class="jnab">
                    <h2>Aerobotix</h2>
                    <p>Learn.Create.Innovate</p>
                </div>
            </div>
        </a>
        <a href="<?= BASE_URL ?>pages/clubs/secu.php" class="link">
            <div class="card">
                <div class="img">
                    <div class="logo secu">
                        <img src="<?= BASE_URL ?>assets/img/secu-logo.png" alt="securinets logo">
                    </div>
                </div>
                <div class="jnab">
                    <h2>Securinets</h2>
                    <p>Cybersecurity & Ethical Hacking</p>
                </div>
            </div>
        </a>
        <a href="<?= BASE_URL ?>pages/clubs/ieee.php" class="link">
            <div class="card">
                <div class="img">
                    <div class="logo">
                        <img src="<?= BASE_URL ?>assets/img/ieee-logo.png" alt="ieee logo">
                    </div>
                </div>
                <div class="jnab">
                    <h2>IEEE</h2>
                    <p>Advancing Technology for Humanity</p>
                </div>
            </div>
        </a>
        <a href="<?= BASE_URL ?>pages/clubs/acm.php" class="link">
            <div class="card">
                <div class="img">
                    <div class="logo">
                        <img src="<?= BASE_URL ?>assets/img/acm-logo.png" alt="acm logo">
                    </div>
                </div>
                <div class="jnab">
                    <h2>ACM</h2>
                    <p>Computing for Innovation</p>
                </div>
            </div>
        </a>

        <a href="<?= BASE_URL ?>pages/clubs/cim.php" class="link">
            <div class="card">
                <div class="img">
                    <div class="logo">
                        <img src="<?= BASE_URL ?>assets/img/cim.png" alt="CIM Insat">
                    </div>
                </div>
                <div class="jnab">
                    <h2>CIM </h2>
                    <p>Industrial Computing & Mechatronics</p>
                </div>
            </div>
        </a>
        <a href="<?= BASE_URL ?>pages/clubs/theatro.php" class="link">
            <div class="card">
                <div class="img">
                    <div class="logoth">
                        <img src="<?= BASE_URL ?>assets/img/theatro.jpg" alt="Theatro Insat">
                    </div>
                </div>
                <div class="jnab">
                    <h2>Theatro</h2>
                    <p>Unleash Your Inner Actor</p>
                </div>
            </div>
        </a>

        <a href="<?= BASE_URL ?>pages/clubs/press.php" class="link">
            <div class="card">
                <div class="img">
                    <div class="logoth">
                        <img src="<?= BASE_URL ?>assets/img/press.png" alt="Insat Press">
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


<?php require_once ROOT_PATH . '/views/footer.php'; ?>