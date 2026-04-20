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
                <form action="../backend/logout.php" method="POST" style="display:flex; align-items:center; gap:20px;">
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

                <form action="../backend/broadcast.php" method="POST">
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
                    <input class="eb-input" id="subjectInput" type="text" name="subject" placeholder="e.g. Club meeting this Friday" >
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
                <div>
                  <h2 class="events-title">Manage Events</h2>
                  <p class="events-subtitle">View, edit, and delete your club events</p>
                </div>
              </div>

              <div class="events-grid">
                <?php 
                require_once '../backend/getEvents.php';

                $events = fetchEventsData();
                
                foreach ($events as $event): 
                    //echo "<script>console.log(".json_encode($event).");</script>";
                ?>
                <div class="event-card">
                  <div class="event-card-header">
                    <span class="event-type-badge event-type-<?php echo strtolower($event["event"]['event_type']); ?>">
                      <?php echo htmlspecialchars($event["event"]['event_type']); ?>
                    </span>
                    <div class="event-actions">
                      <button class="event-action-btn event-edit-btn" onclick="editEvent(<?php echo $event['id']; ?>)" title="Edit event">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                      </button>
                      <button class="event-action-btn event-delete-btn" onclick="confirmDeleteEvent('<?php echo htmlspecialchars($event["event"]['title'], ENT_QUOTES); ?>')" title="Delete event">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <polyline points="3 6 5 6 21 6"></polyline>
                          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                          <line x1="10" y1="11" x2="10" y2="17"></line>
                          <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                      </button>
                    </div>
                  </div>

                  <h3 class="event-title"><?php echo htmlspecialchars($event['event']["title"]); ?></h3>
                  <p class="event-description"><?php echo htmlspecialchars($event["event"]['description']); ?></p>

                  <div class="event-meta-grid">
                    <div class="event-meta-item">
                      <svg class="event-meta-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                      </svg>
                      <span><?php echo date('M d, Y', strtotime($event["event"]['event_date'])); ?></span>
                    </div>

                    <div class="event-meta-item">
                      <svg class="event-meta-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                      </svg>
                      <span><?php echo date('g:i A', strtotime($event["event"]['event_time'])); ?></span>
                    </div>

                    <?php if (!empty($event["event"]['duration'])): ?>
                    <div class="event-meta-item">
                      <svg class="event-meta-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                      </svg>
                      <span><?php echo $event["event"]['duration']; ?> day<?php echo $event["event"]['duration'] > 1 ? 's' : ''; ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($event["event"]['location'])): ?>
                    <div class="event-meta-item">
                      <svg class="event-meta-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                      </svg>
                      <span><?php echo htmlspecialchars($event["event"]['location']); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($event["event"]['max_attendees'])): ?>
                    <div class="event-meta-item">
                      <svg class="event-meta-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                      </svg>
                      <span>Max <?php echo $event["event"]['max_attendees']; ?> attendees</span>
                    </div>
                    <?php endif; ?>
                  </div>

                  <?php if (!empty($event["event"]['prize_pool'])): ?>
                  <div class="event-prize">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <circle cx="12" cy="8" r="7"></circle>
                      <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                    </svg>
                    <span><?php echo htmlspecialchars($event["event"]['prize_pool']); ?></span>
                  </div>
                  <?php endif; ?>

                  <?php if (!empty($event['clubs'])): ?>
                  <div class="event-clubs">
                    <span class="event-clubs-label">Participating clubs:</span>
                    <div class="event-clubs-list">
                      <?php foreach ($event['clubs'] as $club): ?>
                        <span class="event-club-tag"><?php echo htmlspecialchars($club); ?></span>
                      <?php endforeach; ?>
                    </div>
                  </div>
                  <?php endif; ?>

                <?php if (!empty($event['staff'])): ?>
                    <div class="event-staff-section">
                      <span class="event-staff-header">Staff members</span>
                      <div class="event-staff-grid">
                        <?php foreach ($event['staff'] as $staffMember): ?>
                          <div class="staff-card">
                            <div class="staff-photo">
                              <img src="<?php echo htmlspecialchars($staffMember['photo'] ?? '../assets/img/default-avatar.png'); ?>" alt="<?php echo htmlspecialchars($staffMember['name']); ?>">
                            </div>
                            <div class="staff-info">
                              <p class="staff-name"><?php echo htmlspecialchars($staffMember['name']); ?></p>
                              <p class="staff-role"><?php echo htmlspecialchars($staffMember['role']); ?></p>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
                <?php endforeach; ?>

                <?php if (empty($events)): ?>
                <div class="events-empty">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                  </svg>
                  <h3>No events yet</h3>
                  <p>Create your first event to get started</p>
                </div>
                <?php endif; ?>
              </div>
            </section>

            <div id="deleteModal" class="delete-modal-overlay">
              <div class="delete-modal">
                <div class="delete-modal-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                  </svg>
                </div>
                <h3 class="delete-modal-title">Delete Event</h3>
                <p class="delete-modal-text">Are you sure you want to delete "<span id="deleteEventName"></span>"? This action cannot be undone.</p>
                <div class="delete-modal-actions">
                  <button class="delete-modal-cancel" onclick="closeDeleteModal()">Cancel</button>
                  <button class="delete-modal-confirm" onclick="deleteEvent()">Delete Event</button>
                </div>
              </div>
            </div>
    </main>

    <div id="broadcastModal" class="bm-overlay">
      <div class="bm-dialog">

        <div class="bm-header">
          <div class="bm-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2.2">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
              <polyline points="22,6 12,13 2,6"/>
            </svg>
          </div>
          <div>
            <p class="bm-title">Confirm broadcast</p>
            <p class="bm-subtitle">Review before sending</p>
          </div>
        </div>

        <div class="bm-summary">
          <div class="bm-row">
            <span class="bm-row-label">To</span>
            <span id="modal-receivers" class="bm-row-value"></span>
          </div>
          <div class="bm-row">
            <span class="bm-row-label">Subject</span>
            <span id="modal-subject" class="bm-row-value"></span>
          </div>
          <div class="bm-row">
            <span class="bm-row-label">Preview</span>
            <span id="modal-preview" class="bm-row-value bm-row-value--muted"></span>
          </div>
        </div>

        <div class="bm-warning">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="2.2" class="bm-warning-icon">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
            <line x1="12" y1="9" x2="12" y2="13"/>
            <line x1="12" y1="17" x2="12.01" y2="17"/>
          </svg>
          <span>This email will be sent to all members in the selected group. This action cannot be undone.</span>
        </div>

        <div class="bm-footer">
          <button class="bm-btn-cancel" onclick="closeModal()">Cancel</button>
          <button class="bm-btn-send" onclick="confirmSend()">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <line x1="22" y1="2" x2="11" y2="13"/>
              <polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
            Send broadcast
          </button>
        </div>

      </div>
    </div>
    <div id="toastContainer" class="toast-container">

      <div id="successToast" class="toast toast-success">
        <div class="toast-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
        </div>
        <div class="toast-content">
          <p class="toast-title">Broadcast sent successfully!</p>
          <p class="toast-message">Your message has been delivered to all selected members.</p>
        </div>
        <button class="toast-close" onclick="hideToast('successToast')">&times;</button>
      </div>

      <div id="errorToast" class="toast toast-error">
        <div class="toast-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
          </svg>
        </div>
        <div class="toast-content">
          <p class="toast-title">Broadcast failed</p>
          <p class="toast-message">There was an error sending your message. Please try again.</p>
        </div>
        <button class="toast-close" onclick="hideToast('errorToast')">&times;</button>
      </div>
    </div>
    <script src="../assets/js/admin.js" dref></script>
</body>
</html>
