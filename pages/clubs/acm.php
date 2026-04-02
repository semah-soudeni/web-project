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
        <button id="nav-signin-btn" onclick="window.location.href='../signin.html'" class="signin-btn">Sign
          In</button>
        <button id="nav-signup-btn" onclick="window.location.href='../signup.html'" class="signup-btn">Sign
          Up</button>
        <div id="nav-user-area" style="display:none;align-items:center;gap:15px;">
          <span id="nav-user-name" style="font-weight:600;"></span>
          <button id="nav-logout-btn">Logout</button>
        </div>
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