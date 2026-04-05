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
  <link rel="stylesheet" href="../../assets/css/acmtest.css">
  <title>ACM INSAT</title>
</head>

<body>

  <!-- Hero background (separate layer to avoid scroll conflicts) -->

  <div class="hero-bg"></div>

  <!-- Background particle canvas -->

  <div class="background-canvas">
    <canvas id="bg-canvas"></canvas>
  </div>

  <!-- Scroll follower canvas -->

  <div class="parallax">
    <canvas id="scroll-follower"></canvas>
  </div>

  <!-- Navigation -->

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
      <button class="cta-btn big" onclick="window.joinClub('acm')">Join Now — Free</button>
    </section>

  </main>

  <script src="../../assets/js/acm.js"></script>
  <script src="../../assets/js/auth.js"></script>

</body>

</html>