<?php
session_start();
require_once __DIR__ . '/../backend/bd.php';

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

$selectedClub = strtolower(trim((string)($_GET['club'] ?? 'all')));
$allowedClubs = ['all', 'aero', 'secu', 'ieee', 'acm', 'android', 'cim'];
if (!in_array($selectedClub, $allowedClubs, true)) {
    $selectedClub = 'all';
}

$clubColors = [
    'secu' => '#E74E25',
    'aero' => '#3280C2',
    'ieee' => '#362B69',
    'acm' => '#7DF0CA',
    'android' => '#78DE85',
    'cim' => '#F6C011',
];

$clubNames = [
    'aero' => 'Aerobotix',
    'secu' => 'Securinets',
    'ieee' => 'IEEE',
    'acm' => 'ACM',
    'android' => 'Android Club',
    'cim' => 'CIM',
];

$events = [];
$queryError = false;

try {
    $conn = ConnexionBD::getInstance();

    if ($selectedClub !== 'all') {
        $stmt = $conn->prepare(
            "SELECT e.id, c.slug AS club, e.title, e.description,
                    e.event_date AS date, e.event_time AS time,
                    e.location, e.attendees
             FROM events e
             JOIN clubs c ON e.club_id = c.id
             WHERE c.slug = ?
             ORDER BY e.event_date, e.event_time"
        );
        $stmt->execute([$selectedClub]);
    } else {
        $stmt = $conn->query(
            "SELECT e.id, c.slug AS club, e.title, e.description,
                    e.event_date AS date, e.event_time AS time,
                    e.location, e.attendees
             FROM events e
             JOIN clubs c ON e.club_id = c.id
             ORDER BY e.event_date, e.event_time"
        );
    }

    $events = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (Throwable $e) {
    $queryError = true;
}

$groupedEvents = [];
foreach ($events as $event) {
    $timestamp = strtotime((string)($event['date'] ?? ''));
    if ($timestamp === false) {
        $monthYear = 'Unknown Date';
        $sortKey = '9999-12';
    } else {
        $monthYear = date('F Y', $timestamp);
        $sortKey = date('Y-m', $timestamp);
    }

    if (!isset($groupedEvents[$monthYear])) {
        $groupedEvents[$monthYear] = [
            'sort' => $sortKey,
            'items' => [],
        ];
    }

    $groupedEvents[$monthYear]['items'][] = $event;
}

uasort($groupedEvents, static function (array $a, array $b): int {
    return strcmp($a['sort'], $b['sort']);
});

function formatEventTime(?string $time): string
{
    if (!$time) {
        return 'TBA';
    }

    $parsed = strtotime($time);
    if ($parsed === false) {
        return 'TBA';
    }

    return date('g:i A', $parsed);
}

function escapeText(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/events.css">
    <title>Upcoming Events</title>
</head>

<body>
    <nav class="navigation">
        <div class="nav-container">
            <div class="nav-menu">
                <a href="/index.php" class="nav-link">Clubs</a>
                <a href="/pages/events.php" class="nav-link">Events</a>
                <a href="/pages/map.php" class="nav-link">Map</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="/pages/admin.php" class="nav-link">Admin Dashboard</a>
                <?php endif; ?>
            </div>
            <div class="nav-login">
                 <?php if ($isLoggedIn): ?>
                    <form action="/backend/logout.php" method="POST" style="display:flex; align-items:center; gap:20px;">
                        <span id="nav-user-name" style="font-weight:600;">
                            <?php echo "Hi, " .htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <button id="nav-logout-btn" class="signin-btn">Sign Out</button>
                        </form>
                        <?php else: ?>
                    <a href="/pages/signin.php" class="signin-btn">Sign In</a>
                    <a href="/pages/signup.php" class="signup-btn">Sign Up</a>
                    <?php endif; ?>
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
                <form method="GET" action="/pages/events.php">
                    <button class="filter-btn <?php echo $selectedClub === 'all' ? 'active' : ''; ?>" type="submit" name="club" value="all">All Clubs</button>
                </form>
                <form method="GET" action="/pages/events.php">
                    <button class="filter-btn <?php echo $selectedClub === 'aero' ? 'active' : ''; ?>" type="submit" name="club" value="aero">Aerobotix</button>
                </form>
                <form method="GET" action="/pages/events.php">
                    <button class="filter-btn <?php echo $selectedClub === 'secu' ? 'active' : ''; ?>" type="submit" name="club" value="secu">Securinets</button>
                </form>
                <form method="GET" action="/pages/events.php">
                    <button class="filter-btn <?php echo $selectedClub === 'ieee' ? 'active' : ''; ?>" type="submit" name="club" value="ieee">IEEE</button>
                </form>
                <form method="GET" action="/pages/events.php">
                    <button class="filter-btn <?php echo $selectedClub === 'acm' ? 'active' : ''; ?>" type="submit" name="club" value="acm">ACM</button>
                </form>
                <form method="GET" action="/pages/events.php">
                    <button class="filter-btn <?php echo $selectedClub === 'android' ? 'active' : ''; ?>" type="submit" name="club" value="android">Android Club</button>
                </form>
                <form method="GET" action="/pages/events.php">
                    <button class="filter-btn <?php echo $selectedClub === 'cim' ? 'active' : ''; ?>" type="submit" name="club" value="cim">CIM</button>
                </form>
            </div>
        </div>
    </section>
    <section class="event-section">
        <div class="container">
            <div id="events-container">
                <?php if ($queryError): ?>
                    <div class="empty-state">
                        <h3>Could not load events</h3>
                        <p>Please make sure your database server is running.</p>
                    </div>
                <?php elseif (empty($groupedEvents)): ?>
                    <div class="empty-state">
                        <h3>No events found</h3>
                        <p>There are no upcoming events for this club at the moment.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($groupedEvents as $monthYear => $group): ?>
                        <div class="month-group">
                            <h2 class="month-title"><?php echo escapeText($monthYear); ?></h2>

                            <?php foreach ($group['items'] as $event): ?>
                                <?php
                                $slug = strtolower((string)($event['club'] ?? ''));
                                $clubColor = $clubColors[$slug] ?? '#3182ce';
                                $clubLabel = $clubNames[$slug] ?? strtoupper($slug);

                                $eventDate = strtotime((string)($event['date'] ?? ''));
                                $day = $eventDate !== false ? date('j', $eventDate) : '--';
                                $month = $eventDate !== false ? strtoupper(date('M', $eventDate)) : 'TBA';
                                ?>
                                <div class="event-card" data-club="<?php echo escapeText($slug); ?>">
                                    <div class="date-badge">
                                        <div class="day-badge"><?php echo escapeText((string)$day); ?></div>
                                        <div class="month-badge"><?php echo escapeText($month); ?></div>
                                    </div>

                                    <div class="event-content">
                                        <div class="event-header">
                                            <div class="club-tag <?php echo escapeText($slug); ?>" style="background-color: <?php echo escapeText($clubColor); ?>;">
                                                <?php echo escapeText($clubLabel); ?>
                                            </div>
                                            <div class="event-hour">🕐 <?php echo escapeText(formatEventTime($event['time'] ?? null)); ?></div>
                                        </div>

                                        <h3 class="event-title"><?php echo escapeText($event['title'] ?? 'Untitled Event'); ?></h3>
                                        <p class="event-desc"><?php echo escapeText($event['description'] ?? ''); ?></p>

                                        <div class="event-footer">
                                            <div class="event-location">
                                                <span class="location-icon">📍</span>
                                                <span><?php echo escapeText($event['location'] ?? 'TBA'); ?></span>
                                            </div>

                                            <div class="event-location">
                                                <span>👥</span>
                                                <span><?php echo escapeText((string)($event['attendees'] ?? 0)); ?> attendees</span>
                                            </div>

                                            <a class="register-btn" href="/pages/event-registration.html?event_id=<?php echo (int)($event['id'] ?? 0); ?>">Register</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</body>

</html>
