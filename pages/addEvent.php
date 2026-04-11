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
    <link rel="stylesheet" href="../assets/css/addEvent.css">

    <title>New Event</title>
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
    <section class="">
        <div class="add-event-container">
            <h3 class="add-event-title">Add New Event</h3>
            <form >
                <div>    
                    <p>Event Type</p>
                    <select name="Event Type" > 
                    <option>Hackathon</option>
                    <option>Competition</option>
                    <option>Conference</option>
                    <option>Workshop</option>
                    </select> 
                </div> 
                <div>
                    <p>Event Description</p>
                    <textarea name="description" rows="8" cols="250" placeholder="Event activities, objectives , .." required></textarea>
                </div>
                <div>
                    <p>Event Title</p>
                    <input type="text" placeholder="Enter event title" required>
                </div>
                <div>
                    <p>Date</p>
                    <input type="date"  required>
                </div>
                <div>
                    <p>Time</p>
                    <input type="time" required>
                </div>
                <div>
                   <p>Duration</p>
                    <input type="number" placeholder="Duration in days"> 
                </div>
                <div>
                    <p>Prize Pool</p>
                    <textarea  name="prize" rows="8" cols="250" placeholder="Winners : 1000DT , runner-up : 500DT .."></textarea>
                </div> 
                <div>    
                    <p>Location</p>
                    <input type="text" placeholder="Enter location" >
                </div> 
                <div>    
                    <p>Max Attendees</p>
                    <input type="number" placeholder="---">
                </div> 
                <div>
                    <p>Other Participated Clubs</p>
                    <div class="clubs-dnd-wrapper">
                        <div class="clubs-column" id="left">
                            <p class="column-label">Available Clubs</p>
                            <div class="list"  data-club="aero">
                                <img src="../assets/img/aero-logo.png" class="logo">
                                <span>Aerobotix</span>
                            </div>
                            <div class="list"  data-club="ieee">
                                <img src="../assets/img/ieee-logo.png" class="logo">
                                <span>IEEE</span>
                            </div>
                            <div class="list"  data-club="acm">
                                <img src="../assets/img/acm-logo.png" class="logo">
                                <span>ACM</span>
                            </div>
                            <div class="list"  data-club="Securinets">
                                <img src="../assets/img/secu-logo.png" class="logo">
                                <span>Securinets</span>
                            </div>
                            <div class="list"  data-club="Théatro">
                                <img src="../assets/img/theatro.jpg"  class="logo">
                                <span>Théatro</span>
                            </div>
                            <div class="list"  data-club="CIM">
                                <img src="../assets/img/cim.png"  class="logo">
                                <span>CIM</span>
                            </div>
                            <div class="list"  data-club="junior">
                                <img src="../assets/img/junior.jpg"  class="logo">
                                <span>Junior Entreprise</span>
                            </div>
                            <div class="list"  data-club="android">
                                <img src="../assets/img/andr.png"  class="logo">
                                <span>Android</span>
                            </div>
                            <div class="list"  data-club="enactus">
                                <img src="../assets/img/enactus.png"  class="logo">
                                <span>Enactus</span>
                            </div>
                            <div class="list"  data-club="Press">
                                <img src="../assets/img/press.png"  class="logo">
                                <span>Insat Press</span>
                            </div>
                            <div class="list"  data-club="Lion">
                                <img src="../assets/img/lion.jpg"  class="logo">
                                <span>Lion</span>
                            </div>
                            <div class="list"  data-club="Ciné">
                                <img src="../assets/img/cine.jpg"  class="logo">
                                <span>Ciné Radio</span>
                            </div>
                            <div class="list"  data-club="jci">
                                <img src="../assets/img/juniorc.jpg"  class="logo">
                                <span>JCI</span>
                            </div>
                        </div>
                        <div class="clubs-divider">
                            <span class="arrow-icon">&#8594;</span>
                        </div>
                        <div class="clubs-column" id="right">
                            <p class="column-label">Participating</p>
                            <p class="drop-hint" id="drop-hint">Drop clubs here</p>
                        </div>
                    </div>
                </div>
                <input type="submit" value="Add Event">
            </form>
        </div>
    </section>
    <script>
    (function () {
        const leftBox  = document.getElementById("left");
        const rightBox = document.getElementById("right");
        const dropHint = document.getElementById("drop-hint");

        let dragged   = null;   // the card being moved
        let ghost     = null;   // the visual clone following the cursor
        let offsetX   = 0;
        let offsetY   = 0;

        function updateHint() {
            dropHint.style.display =
                rightBox.querySelectorAll(".list").length > 0 ? "none" : "block";
        }

        function createGhost(card) {
            const g = card.cloneNode(true);
            g.style.cssText = `
                position: fixed;
                pointer-events: none;
                opacity: 0.75;
                z-index: 9999;
                width: ${card.offsetWidth}px;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 6px 20px rgba(0,0,0,0.15);
                padding: 8px 12px;
                display: flex;
                align-items: center;
                gap: 10px;
            `;
            document.body.appendChild(g);
            return g;
        }

        function getColumn(x, y) {
            const rRect = rightBox.getBoundingClientRect();
            const lRect = leftBox.getBoundingClientRect();
            if (x >= rRect.left && x <= rRect.right && y >= rRect.top && y <= rRect.bottom) return rightBox;
            if (x >= lRect.left && x <= lRect.right && y >= lRect.top && y <= lRect.bottom) return leftBox;
            return null;
        }

        function onMouseDown(e) {
            const card = e.currentTarget;
            e.preventDefault();

            dragged = card;
            const rect = card.getBoundingClientRect();
            offsetX = e.clientX - rect.left;
            offsetY = e.clientY - rect.top;

            ghost = createGhost(card);
            ghost.style.left = (e.clientX - offsetX) + "px";
            ghost.style.top  = (e.clientY - offsetY) + "px";

            card.style.opacity = "0.3";
            console.log("card opacity set to 0.3");
            document.addEventListener("mousemove", onMouseMove);
            document.addEventListener("mouseup",   onMouseUp);
        }

        function onMouseMove(e) {
            if (!ghost) return;
            ghost.style.left = (e.clientX - offsetX) + "px";
            ghost.style.top  = (e.clientY - offsetY) + "px";

            // highlight drop zones
            [leftBox, rightBox].forEach(col => col.classList.remove("drag-over"));
            const target = getColumn(e.clientX, e.clientY);
            if (target) target.classList.add("drag-over");
        }

        function onMouseUp(e) {
            document.removeEventListener("mousemove", onMouseMove);
            document.removeEventListener("mouseup",   onMouseUp);

            if (ghost) { ghost.remove(); ghost = null; }

            [leftBox, rightBox].forEach(col => col.classList.remove("drag-over"));

            if (dragged) {
                dragged.style.opacity = "1";
                const target = getColumn(e.clientX, e.clientY);
                if (target && target !== dragged.parentElement) {
                    target.appendChild(dragged);
                }
                dragged = null;
                updateHint();
            }
        }

        document.querySelectorAll(".list").forEach(card => {
            card.style.opacity = "1"; 
            card.addEventListener("mousedown", onMouseDown);
        });

        updateHint();
    })();
    </script>
</body>
</html>
