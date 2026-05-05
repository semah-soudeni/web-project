<?php
require_once __DIR__ . '/../../includes/init.php';

$pageTitle  = 'Club INSAT Maintenance';
$extraCss   = [BASE_URL . 'assets/css/cim.css'];
$extraJs    = [BASE_URL . 'assets/js/cim.js'];
?>

<?php require_once ROOT_PATH . '/views/header.php'; ?>

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

<?php require_once ROOT_PATH . '/views/footer.php'; ?>