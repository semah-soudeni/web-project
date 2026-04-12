<?php
    session_start();
    $isLoggedIn = isset($_SESSION['logged']) && $_SESSION['logged'] === 'yes';
    $displayName = '';
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
        header("location:error.php?code=401");    
        exit;
    }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <title>Dashboard</title>
</head>
<body>
    <nav class="navigation">
        <div class="nav-container">
            <div class="nav-menu">
                <a href="../index.php" class="nav-link">Clubs</a>
                <a href="events.php" class="nav-link">Events</a>
                <a href="map.php" class="nav-link">Map</a>
                <a href="admin.php" class="nav-link">Admin Dashboard</a>
            </div>
            <div class="nav-login">
               <?php if ($isLoggedIn): ?>
                <form action="backend/logout.php" method="POST" style="display:flex; align-items:center; gap:20px;">
                    <span id="nav-user-name" style="font-weight:600;">
                        <?php echo "Hi, " .htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <button id="nav-logout-btn" class="signin-btn">Sign Out</button>
                    </form>
                    <?php else: ?>
                <a href="signin.php" class="signin-btn">Sign In</a>
                <a href="signup.php" class="signup-btn">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

        <main class="dashboard">
            <section class="hero">
                  <div class="grid-overlay"></div>
                  <div class="hero-content">
                    <div class="hero-badge">
                      <!-- star SVG here -->
                      Admin Dashboard
                    </div>
                    <h1 class="hero-title">
                      Bring your community<br>
                      <span>together with events</span>
                    </h1>
                    <p class="hero-subtitle">
                      Create, manage, and promote events that your club members will love.
                      Every great gathering starts with a single click.
                    </p>
                    <div class="hero-actions">
                      <a class="btn-primary" href="addEvent.php">
                        <!-- plus icon SVG -->
                        Create an event
                      </a>
                      <a class="btn-secondary" href="events.php">
                        <!-- calendar icon SVG -->
                        View all events
                      </a>
                    </div>
                    <div class="stats-row">
                      <div class="stat">
                      </div>
                    </div>
                  </div>
                </section>
        <section class="email-broadcast">
              <div class="eb-grid"></div>
              <div class="eb-glow"></div>

              <div class="eb-left">
                <div class="eb-badge">
                  <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                  </svg>
                  Email Broadcast
                </div>
                <h2 class="eb-title">Mail your members<br>with one click</h2>
                <p class="eb-sub">Send announcements, event updates, or newsletters directly to your club members.</p>
                <button class="eb-open-btn" id="openBtn" onclick="togglePanel()">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                  </svg>
                  Compose broadcast
                </button>
              </div>

              <div class="eb-panel" id="ebPanel">
                <div class="eb-panel-head">
                  <div class="eb-panel-title">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.6)" stroke-width="2">
                      <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                      <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    New broadcast
                  </div>
                  <button class="eb-close-btn" onclick="togglePanel()">&#x2715;</button>
                </div>

                <form action="backend/broadcast.php" method="POST">
                  <div class="eb-field">
                    <label class="eb-label">Receivers</label>
                    <div class="eb-dropdown" id="receiverDropdown">
                      <div class="eb-dropdown-trigger" id="dropdownTrigger" onclick="toggleDropdown()">
                        <span id="dropdownLabel" class="placeholder">Select recipient group…</span>
                        <svg class="eb-dropdown-chevron" viewBox="0 0 24 24" fill="none" stroke-width="2.5">
                          <polyline points="6 9 12 15 18 9"/>
                        </svg>
                      </div>
                      <div class="eb-dropdown-menu" id="dropdownMenu">
                        <div class="eb-dropdown-option" onclick="selectOption(this, 'all', 'All members')">
                          <div class="opt-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13">
                              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                              <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                          </div>
                          <div class="opt-meta">
                            <span class="opt-name">All members</span>
                            <span class="opt-desc">Every registered club member</span>
                          </div>
                        </div>
                        <div class="eb-dropdown-option" onclick="selectOption(this, 'leaders', 'Club leaders')">
                          <div class="opt-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13">
                              <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                          </div>
                          <div class="opt-meta">
                            <span class="opt-name">Club leaders</span>
                            <span class="opt-desc">Admins and club presidents only</span>
                          </div>
                        </div>
                        <div class="eb-dropdown-option" onclick="selectOption(this, 'active', 'Active members')">
                          <div class="opt-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13">
                              <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                            </svg>
                          </div>
                          <div class="opt-meta">
                            <span class="opt-name">Active members</span>
                            <span class="opt-desc">Attended at least one event</span>
                          </div>
                        </div>
                        <div class="eb-dropdown-option" onclick="selectOption(this, 'new', 'New members')">
                          <div class="opt-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13">
                              <circle cx="12" cy="12" r="10"/>
                              <line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
                            </svg>
                          </div>
                          <div class="opt-meta">
                            <span class="opt-name">New members</span>
                            <span class="opt-desc">Joined in the last 30 days</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <input type="hidden" id="receiverValue" name="receivers" value="">
                  </div>

                  <div class="eb-field">
                    <label class="eb-label">Subject</label>
                    <input class="eb-input" id="subjectInput" type="text" name="subject" placeholder="e.g. Club meeting this Friday" required>
                  </div>

                  <div class="eb-field">
                    <label class="eb-label">Message</label>
                    <textarea class="eb-textarea" id="msgInput" name="message" placeholder="Write your message here…" required></textarea>
                  </div>

                  <div class="eb-footer">
                    <span class="eb-char-count" id="charCount">0 characters</span>
                    <button type="submit" class="eb-send-btn">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="13" height="13">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                      </svg>
                      Send
                    </button>
                  </div>
                </form>
              </div>
            </section> 
        <section class="events-section">
                <div class="events-header">
                    <div class="events-header-left">
                        <h3 class="title">Upcoming Events</h3>
                    </div>
                </div>
                <div class="events-container">
                    <div class="event-card">
                        <div class="event-card-accent"></div>
                        <div class="event-card-body">
                            <div class="event-card-header">
                                <span class="event-tag">General</span>
                                <div class="more-menu">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#718096"><path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/></svg>
                                </div>
                            </div>
                            <h4 class="event-title">Weekly General Meeting</h4>
                            <div class="event-meta-grid">
                                <div class="event-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#718096"><path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z"/></svg>
                                    <span>Feb 10, 2026</span>
                                </div>
                                <div class="event-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#718096"><path d="M339.5-108.5q-65.5-28.5-114-77t-77-114Q120-365 120-440t28.5-140.5q28.5-65.5 77-114t114-77Q405-800 480-800t140.5 28.5q65.5 28.5 114 77t77 114Q840-515 840-440t-28.5 140.5q-28.5 65.5-77 114t-114 77Q555-80 480-80t-140.5-28.5ZM480-440Zm112 168 56-56-128-128v-184h-80v216l152 152ZM224-866l56 56-170 170-56-56 170-170Zm512 0 170 170-56 56-170-170 56-56ZM480-160q117 0 198.5-81.5T760-440q0-117-81.5-198.5T480-720q-117 0-198.5 81.5T200-440q0 117 81.5 198.5T480-160Z"/></svg>
                                    <span>6:00 PM</span>
                                </div>
                                <div class="event-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#718096"><path d="M536.5-503.5Q560-527 560-560t-23.5-56.5Q513-640 480-640t-56.5 23.5Q400-593 400-560t23.5 56.5Q447-480 480-480t56.5-23.5ZM480-186q122-112 181-203.5T720-552q0-109-69.5-178.5T480-800q-101 0-170.5 69.5T240-552q0 71 59 162.5T480-186Zm0 106Q319-217 239.5-334.5T160-552q0-150 96.5-239T480-880q127 0 223.5 89T800-552q0 100-79.5 217.5T480-80Zm0-480Z"/></svg>
                                    <span>Room 2B6-1</span>
                                </div>
                                <div class="event-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#718096"><path d="M40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm720 0v-120q0-44-24.5-84.5T666-434q51 6 96 20.5t84 35.5q36 20 55 44.5t19 53.5v120H760ZM247-527q-47-47-47-113t47-113q47-47 113-47t113 47q47 47 47 113t-47 113q-47 47-113 47t-113-47Zm466 0q-47 47-113 47-11 0-28-2.5t-28-5.5q27-32 41.5-71t14.5-81q0-42-14.5-81T544-792q14-5 28-6.5t28-1.5q66 0 113 47t47 113q0 66-47 113ZM120-240h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm296.5-343.5Q440-607 440-640t-23.5-56.5Q393-720 360-720t-56.5 23.5Q280-673 280-640t23.5 56.5Q327-560 360-560t56.5-23.5ZM360-240Zm0-400Z"/></svg>
                                    <span>45 attendees</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
    </main>
    <script src="../assets/js/admin.js" dref></script>
</body>
</html>
