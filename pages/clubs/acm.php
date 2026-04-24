<?php
require_once __DIR__ . '/../../includes/init.php';

$pageTitle  = 'ACM INSAT';
$extraCss   = [BASE_URL . 'assets/css/acmtest.css'];
$extraJs    = [BASE_URL . 'assets/js/acm.js'];

?>

<?php require_once ROOT_PATH . '/views/header.php'; ?>

<div class="hero-bg"></div>

<div class="background-canvas">
  <canvas id="bg-canvas"></canvas>
</div>

<!-- Hero section -->

<section class="hero">
  <div class="hero-overlay"></div>

  <div class="hero-content glass">
    <h1>ACM INSAT</h1>
    <p>
      A community of builders and problem solvers exploring
      modern computing through projects, competitions,
      and collaborative innovation.
    </p>
    <button class="ctaj-btn">Explore the Club</button>
  </div>
</section>

<main class="main">

  <section class="glass section">
    <h2>Our Philosophy</h2>

    ```
    <div class="grid-3">

      <div class="card">
        <h3>⚡ Learn by Building</h3>
        <p>
          Every workshop creates something real —
          apps, tools, and systems.
        </p>
      </div>

      <div class="card">
        <h3>🤝 Collaborative Growth</h3>
        <p>
          Members share knowledge and grow
          through teamwork and experimentation.
        </p>
      </div>

      <div class="card">
        <h3>🚀 Future Focused</h3>
        <p>
          We explore AI, engineering,
          and competitive programming.
        </p>
      </div>

    </div>
    ```

  </section>

  <section class="glass section stats">
    <h2>Community Impact</h2>

    ```
    <div class="stats-grid">
      <div class="stat">
        <span>50+</span>
        <p>Members</p>
      </div>

      <div class="stat">
        <span>20+</span>
        <p>Workshops</p>
      </div>

      <div class="stat">
        <span>10+</span>
        <p>Competitions</p>
      </div>

      <div class="stat">
        <span>5+</span>
        <p>Events</p>
      </div>
    </div>
    ```

  </section>

  <section class="glass section join">
    <h2>Join ACM INSAT</h2>
    <p>Become part of a community that builds the future.</p>
    <a href="<?= BASE_URL ?>backend/ajouter.php">
      <button class="cta-btn big" onclick="window.joinClub('acm')">Join Now — Free</button>

    </a>
  </section>

</main>
<?php require_once ROOT_PATH . '/views/footer.php'; ?>