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

$allEvents = [];
$res2 = [];
$count = [];
$queryError = false;

try {
    $conn = ConnexionBD::getInstance();

    $stmt = $conn->query(
        "SELECT e.id, c.slug AS club, e.title, e.description,
                e.event_date AS date, e.event_time AS time,
                e.location
         FROM events e
         JOIN club_events ce ON e.id = ce.event_id
         JOIN clubs c ON ce.club_id = c.id
         ORDER BY e.event_date, e.event_time"
    );

    if ($isLoggedIn && isset($_SESSION["id"])) {
        $stmt2 = $conn->prepare(
            "SELECT event_id
            FROM register R
            WHERE R.user_id = :id"
        );
        $stmt2->execute(['id' => $_SESSION["id"]]);
        $res2 = $stmt2->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    $stmt3 = $conn->prepare(
        "SELECT event_id,COUNT(*) AS nb 
        FROM register
        GROUP BY  event_id;"
        );

    $stmt3->execute();

    $count = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    $allEvents = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (Throwable $e) {
    $queryError = true;
}

$registeredIds = array_map('intval', $res2);

    $events = $allEvents;
    if ($selectedClub !== 'all') {
        $events = array_values(array_filter(
            $allEvents,
            static fn(array $event): bool => strtolower((string)($event['club'] ?? '')) === $selectedClub
    ));
}

    foreach ($allEvents as &$evt) {
        $evt['is_registered'] = in_array((int)$evt['id'], $registeredIds, true);
        
        $att = 0;
        foreach($count as $elem) {
            if($elem["event_id"] == $evt["id"]){
                $att = $elem["nb"];
                break;
            }
        }
        $evt['attendees'] = $att;
    }
    unset($evt);

    foreach ($events as &$evt2) {
        $evt2['is_registered'] = in_array((int)$evt2['id'], $registeredIds, true);
        
        $att2 = 0;
        foreach($count as $elem) {
            if($elem["event_id"] == $evt2["id"]){
                $att2 = $elem["nb"];
                break;
            }
        }
        $evt2['attendees'] = $att2;
    }
    unset($evt2);

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

$eventsPayload = [
    'selectedClub' => $selectedClub,
    'queryError' => $queryError,
    'events' => $allEvents,
];
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/events.css">
    <script>
        window.PHP_EVENTS_PAYLOAD = <?php echo json_encode($eventsPayload, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    </script>
    <script src="../assets/js/events.js" defer></script>
    <title>Upcoming Events</title>
</head>

<body>
    <nav class="navigation">
        <div class="nav-container">
            <div class="nav-menu">
                <a href="../index.php" class="nav-link">Clubs</a>
                <a href="events.php" class="nav-link">Events</a>
                <a href="map.php" class="nav-link">Map</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="admin.php" class="nav-link">Admin Dashboard</a>
                <?php endif; ?>
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

    <section class="header-section">
        <div class="header-container">
            <h1 class="header-title">Upcoming Events</h1>
            <p class="header-subtitle">Discover and participate in exciting events from all our clubs</p>
        </div>
    </section>

    <section class="filter-section">
        <div class="container">
            <div class="filters">
                <button class="filter-btn <?php echo $selectedClub === 'all' ? 'active' : ''; ?>" type="button" data-club="all">All Clubs</button>
                <button class="filter-btn <?php echo $selectedClub === 'aero' ? 'active' : ''; ?>" type="button" data-club="aero">Aerobotix</button>
                <button class="filter-btn <?php echo $selectedClub === 'secu' ? 'active' : ''; ?>" type="button" data-club="secu">Securinets</button>
                <button class="filter-btn <?php echo $selectedClub === 'ieee' ? 'active' : ''; ?>" type="button" data-club="ieee">IEEE</button>
                <button class="filter-btn <?php echo $selectedClub === 'acm' ? 'active' : ''; ?>" type="button" data-club="acm">ACM</button>
                <button class="filter-btn <?php echo $selectedClub === 'android' ? 'active' : ''; ?>" type="button" data-club="android">Android Club</button>
                <button class="filter-btn <?php echo $selectedClub === 'cim' ? 'active' : ''; ?>" type="button" data-club="cim">CIM</button>
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
                                                <?php
                                                    $att = 0;
                                                    foreach($count as $elem) {
                                                        if($elem["event_id"] == $event["id"]){
                                                            $att = $elem["nb"];
                                                        }
                                                    }
                                                ?>
                                                <span><?php echo escapeText((string)$att); ?> attendees</span>
                                            </div>
                                            
                                            <?php if (!$event['is_registered']): ?>
                                                <a class="register-btn" href="event-registration.html?event_id=<?php echo (int)($event['id'] ?? 0); ?>">Register</a>
                                            <?php else: ?>
                                                <a class="register-btn" aria-disabled="true" style="opacity:0.6; pointer-events:none;">Registered ✓</a>
                                            <?php endif; ?>        
                                            
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
