<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/events.css">
    <title>Upcoming Events</title>
</head>

<body>
    <nav class="navigation">
        <div class="nav-container">
            <div class="nav-menu">
                <a href="../index.html" class="nav-link">Clubs</a>
                <a href="events.html" class="nav-link">Events</a>
                <a href="map.html" class="nav-link">Map</a>
                <a href="admin.html" class="nav-link">Admin Dashboard</a>
            </div>
            <div class="nav-login">
                <button id="nav-signin-btn" onclick="window.location.href='signin.html'" class="signin-btn">Sign
                    In</button>
                <button id="nav-signup-btn" onclick="window.location.href='signup.html'" class="signup-btn">Sign
                    Up</button>
                <div id="nav-user-area" style="display:none;align-items:center;gap:10px;">
                    <span id="nav-user-name" style="font-weight:600;"></span>
                    <button id="nav-logout-btn">Logout</button>
                </div>
            </div>
        </div>
    </nav>

    <section class="header-section">
        <div class="header-container">
            <h1 class="header-title">Upcoming Events</h1>
            <p class="header-subtitle">Discover and participate in exciting events from all our clubs</p>
        </div>
    </section>

    <section class="filter-section">
        <div class="container">
            <div class="filters">
                <button class="filter-btn active" data-club="all">All Clubs</button>
                <button class="filter-btn" data-club="aero">Aerobotix</button>
                <button class="filter-btn" data-club="secu">Securinets</button>
                <button class="filter-btn" data-club="ieee">IEEE</button>
                <button class="filter-btn" data-club="acm">ACM</button>
                <button class="filter-btn" data-club="android">Android Club</button>
                <button class="filter-btn" data-club="cim">CIM</button>
            </div>
        </div>
    </section>
    <section class="event-section">
        <div class="container">
            <div id="events-container"></div>
        </div>
    </section>
    <script src="../assets/js/auth.js"></script>
    <script src="../assets/js/events.js"></script>
</body>

</html>