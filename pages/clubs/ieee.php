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
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
        <link rel="stylesheet" href="/assets/css/ieee.css">
    </head>
<body>
<nav>
  <div class="nav-left">
    <a href="../../index.php" class="back-link">
        <svg viewBox="0 0 16 16" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="10 4 6 8 10 12"/>
        </svg>back</a>

    <a href="#" class="nav-brand"><span>IEEE</span></a>
  </div>
    <div class="nav-center">
        <ul>
            <li><a href="#about">About</a></li>
            <li><a href="#objectives">Objectives</a></li>
            <li><a href="#activities">Activities</a></li>
            <li><a href="#team">Team</a></li>
            <li><a href="#policy">Policy</a></li>
        </ul>
    </div>
    <div class="nav-right">
        <a href="ajouter.php" class="nav-cta">Join Now</a>
    </div>
</nav>
    <canvas id="rockets"></canvas>
    <script src="../../assets/js/ieee.js" defer></script>


<section id="hero">
  <div class="hero-glow"></div>
  <div class="hero-glow2"></div>
  <div class="hero-content">
    <div class="hero-tag">Est. 2021 — University Tech Club</div>
    <h1>
      Build.<br>
      <span class="accent">Code.</span><br>
      <span class="accent2">Innovate.</span>
    </h1>
    <p class="hero-desc">
      IEEE is where coders, builders, and digital dreamers come together to shape tomorrow's technology — one commit at a time.
    </p>
    <div class="hero-btns">
      <a href="#join" class="btn-primary">⬡ Join the Club</a>
      <a href="#about" class="btn-outline">↓ Learn More</a>
    </div>
    <div class="live-stats">
      <div class="stat-item">
        <div class="stat-num" id="member-count">148</div>
        <div class="stat-label">Active Members <span class="live-dot" style="display:inline-flex;">LIVE</span></div>
      </div>
      <div class="stat-item">
        <div class="stat-num">24</div>
        <div class="stat-label">Projects Shipped</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">6</div>
        <div class="stat-label">Hackathons Won</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">3+</div>
        <div class="stat-label">Years Active</div>
      </div>
    </div>
  </div>
</section>

<section id="about">
  <div class="fade-in">
    <div class="section-label">Who We Are</div>
    <h2>About IEEE</h2>
    <p class="section-intro">
      IEEE is a student-led tech and coding club dedicated to building a thriving community of developers, engineers, and innovators across all disciplines and skill levels.
    </p>
    <p style="color: var(--muted); font-size:0.95rem; font-weight:300; line-height:1.8; max-width:520px;">
      Whether you're writing your first Hello World or architecting distributed systems, you belong here. We run weekly workshops, competitive hackathons, open-source sprints, and industry speaker sessions — all designed to fast-track your growth and connect you with like-minded builders.
    </p>
  </div>
  <div class="about-visual">
    <div class="about-hex hex1"></div>
    <div class="about-hex hex2"></div>
    <div class="about-hex hex3"></div>
    <div class="hex-center">
      &lt;/&gt;
      <span>IEEE</span>
    </div>
  </div>
</section>

<section id="objectives">
  <div class="fade-in">
    <div class="section-label">Our Goals</div>
    <h2>Club Objectives</h2>
    <p class="section-intro">Six pillars that define everything we build and every decision we make.</p>
  </div>
  <div class="objectives-grid">
    <div class="obj-card fade-in">
      <div class="obj-num">01 ——</div>
      <h3>Skill Development</h3>
      <p>Provide structured learning paths in web dev, AI/ML, cybersecurity, and systems programming for all levels.</p>
    </div>
    <div class="obj-card fade-in">
      <div class="obj-num">02 ——</div>
      <h3>Open Source Culture</h3>
      <p>Contribute to the global developer ecosystem through collaborative open-source projects and public repositories.</p>
    </div>
    <div class="obj-card fade-in">
      <div class="obj-num">03 ——</div>
      <h3>Industry Readiness</h3>
      <p>Bridge the gap between academia and industry through mentorships, mock interviews, and real-world project experience.</p>
    </div>
    <div class="obj-card fade-in">
      <div class="obj-num">04 ——</div>
      <h3>Community Building</h3>
      <p>Foster an inclusive, collaborative environment where every member feels empowered to learn and lead.</p>
    </div>
    <div class="obj-card fade-in">
      <div class="obj-num">05 ——</div>
      <h3>Innovation & Research</h3>
      <p>Encourage members to push boundaries — from AI experiments to hardware prototypes and startup ideas.</p>
    </div>
    <div class="obj-card fade-in">
      <div class="obj-num">06 ——</div>
      <h3>Competitive Excellence</h3>
      <p>Represent the university at national and international hackathons, coding competitions, and CTF challenges.</p>
    </div>
  </div>
</section>

<section id="activities">
  <div class="fade-in">
    <div class="section-label">What We Do</div>
    <h2>Club Activities</h2>
    <p class="section-intro">Recurring programs and events that keep our community sharp and connected.</p>
  </div>
  <div class="activities-list fade-in">
    <div class="activity-row">
      <div class="act-num">01</div>
      <div>
        <div class="act-name">Weekly Code Sessions</div>
        <div class="act-meta">Every Thursday · 6:00 PM — Live coding, pair programming, and tech talks</div>
      </div>
      <div class="act-badge badge-green">Weekly</div>
    </div>
    <div class="activity-row">
      <div class="act-num">02</div>
      <div>
        <div class="act-name">Hack — Internal Hackathon</div>
        <div class="act-meta">Every semester · 48-hour team sprint to build and ship a project</div>
      </div>
      <div class="act-badge badge-red">Semester</div>
    </div>
    <div class="activity-row">
      <div class="act-num">03</div>
      <div>
        <div class="act-name">Open Source Fridays</div>
        <div class="act-meta">Bi-weekly · Contribute to real open-source repos with guided mentorship</div>
      </div>
      <div class="act-badge badge-purple">Bi-weekly</div>
    </div>
    <div class="activity-row">
      <div class="act-num">04</div>
      <div>
        <div class="act-name">Industry Speaker Series</div>
        <div class="act-meta">Monthly · Engineers from top companies share their journeys and insights</div>
      </div>
      <div class="act-badge badge-purple">Monthly</div>
    </div>
    <div class="activity-row">
      <div class="act-num">05</div>
      <div>
        <div class="act-name">CTF (Capture the Flag) Training</div>
        <div class="act-meta">Monthly · Cybersecurity challenges and ethical hacking workshops</div>
      </div>
      <div class="act-badge badge-purple">Monthly</div>
    </div>
    <div class="activity-row">
      <div class="act-num">06</div>
      <div>
        <div class="act-name">AI/ML Study Circle</div>
        <div class="act-meta">Every Tuesday · Paper reviews, model experiments, and ML project showcases</div>
      </div>
      <div class="act-badge badge-green">Weekly</div>
    </div>
    <div class="activity-row">
      <div class="act-num">07</div>
      <div>
        <div class="act-name">End-of-Year Demo Day</div>
        <div class="act-meta">Annual · Public showcase of member projects to sponsors and faculty</div>
      </div>
      <div class="act-badge badge-red">Annual</div>
    </div>
  </div>
</section>

<div id="members-live">
  <div class="section-label" style="justify-content:center;">
    Live Member Count
  </div>
  <div class="members-display" id="live-num">148</div>
  <div class="members-sub">Verified active members &nbsp;·&nbsp; <span class="live-dot">Updated in real-time</span></div>
</div>

<section id="team">
  <div class="fade-in">
    <div class="section-label">Leadership</div>
    <h2>Meet the Team</h2>
    <p class="section-intro">The crew keeping IEEE running — elected each academic year.</p>
  </div>
  <div class="team-grid fade-in">
    <div class="member-card">
      <div class="member-avatar">SR</div>
      <div class="member-name">Sami Rahali</div>
      <div class="member-role">President</div>
    </div>
    <div class="member-card">
      <div class="member-avatar">LB</div>
      <div class="member-name">Lina Ben Ali</div>
      <div class="member-role">Vice President</div>
    </div>
    <div class="member-card">
      <div class="member-avatar">KM</div>
      <div class="member-name">Karim Mansour</div>
      <div class="member-role">Tech Lead</div>
    </div>
    <div class="member-card">
      <div class="member-avatar">AM</div>
      <div class="member-name">Amira Mejri</div>
      <div class="member-role">Events Director</div>
    </div>
    <div class="member-card">
      <div class="member-avatar">YT</div>
      <div class="member-name">Yassine Trabelsi</div>
      <div class="member-role">Dev Ops Lead</div>
    </div>
    <div class="member-card">
      <div class="member-avatar">FZ</div>
      <div class="member-name">Fatma Zahra</div>
      <div class="member-role">Community Manager</div>
    </div>
    <div class="member-card">
      <div class="member-avatar">RK</div>
      <div class="member-name">Rami Khelifi</div>
      <div class="member-role">Communications</div>
    </div>
    <div class="member-card">
      <div class="member-avatar">NA</div>
      <div class="member-name">Nour Abdallah</div>
      <div class="member-role">Treasurer</div>
    </div>
  </div>
</section>

<section id="policy">
  <div class="fade-in">
    <div class="section-label">Rules & Guidelines</div>
    <h2>Club Policy</h2>
    <p class="section-intro">Clear expectations that keep our community productive, respectful, and fun.</p>
  </div>
  <div class="policy-grid fade-in">
    <div>
      <div class="policy-block" style="margin-bottom:2rem;">
        <h3>Membership Rules</h3>
        <ul>
          <li>Membership is open to all enrolled students regardless of major</li>
          <li>Active members must attend at least 60% of weekly sessions per semester</li>
          <li>Annual membership fee: 15 TND (covers materials & events)</li>
          <li>Members may lose status after two unexcused consecutive absences</li>
          <li>Alumni may retain honorary membership with board approval</li>
        </ul>
      </div>
      <div class="policy-block">
        <h3>Code of Conduct</h3>
        <ul>
          <li>Treat every member with respect — harassment of any kind is zero-tolerated</li>
          <li>Plagiarism in club projects results in immediate disqualification</li>
          <li>All public communications must represent the club professionally</li>
          <li>Members must attribute open-source work correctly under applicable licenses</li>
        </ul>
      </div>
    </div>
    <div>
      <div class="policy-block" style="margin-bottom:2rem;">
        <h3>Project & IP Policy</h3>
        <ul>
          <li>Projects built under club resources are co-owned by the member and the club</li>
          <li>Members may open-source their projects with board notification</li>
          <li>Commercial use of club-funded projects requires an agreement</li>
          <li>All code must be stored in the club's GitHub organization</li>
        </ul>
      </div>
      <div class="policy-block">
        <h3>Leadership & Voting</h3>
        <ul>
          <li>Board elections held annually at the end of the academic year</li>
          <li>All active members are eligible to vote and stand for election</li>
          <li>Decisions require a simple majority; ties are broken by the president</li>
          <li>Any member may propose agenda items to the board in writing</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section id="join">
  <div class="join-wrapper">
    <div class="join-info fade-in">
      <div class="section-label">Become a Member</div>
      <h2>Join IEEE</h2>
      <p>Ready to level up? Fill out the form and our team will reach out within 48 hours with your onboarding details.</p>
      <div class="join-perk">
        <div class="perk-icon">⚡</div>
        <div class="perk-text">
          <strong>Instant Slack Access</strong>
          <span>Join our 150+ member Discord/Slack from day one</span>
        </div>
      </div>
      <div class="join-perk">
        <div class="perk-icon">🛠</div>
        <div class="perk-text">
          <strong>Free Learning Resources</strong>
          <span>Curated courses, ebooks, and workshop recordings</span>
        </div>
      </div>
      <div class="join-perk">
        <div class="perk-icon">🏆</div>
        <div class="perk-text">
          <strong>Hackathon Priority Access</strong>
          <span>Reserve your spot in Hack and external events</span>
        </div>
      </div>
      <div class="join-perk">
        <div class="perk-icon">🤝</div>
        <div class="perk-text">
          <strong>Mentorship Matching</strong>
          <span>Get paired with a senior member for guided growth</span>
        </div>
      </div>
    </div>
  </div>
</section>

<footer>
  <a href="#" class="footer-logo">IEEE</a>
  <div class="footer-copy">© 2025 IEEE — All rights reserved</div>
</footer>

</body>
</html>
