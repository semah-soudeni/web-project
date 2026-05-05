<?php
require_once __DIR__ . '/../includes/init.php';

$pageTitle  = 'Upcoming Events';
$activePage = 'events';
$extraCss   = [BASE_URL . 'assets/css/events.css'];
$extraJs    = [BASE_URL . 'assets/js/events.js'];

$selectedClub = strtolower(trim((string)($_GET['club'] ?? 'all')));
$allowedClubs = [
    'all',
    'aero',
    'secu',
    'ieee',
    'acm',
    'android',
    'cim',
    'theatro',
    'cineradio',
    'press',
    'lions',
    'enactus',
    'jei',
    'jci',
    'chem',
    'astro',
    '3zero'
];
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
        "SELECT
            e.id, 
            GROUP_CONCAT(c.slug ORDER BY c.slug SEPARATOR ',') AS clubs,
            GROUP_CONCAT(c.name ORDER BY c.slug SEPARATOR ',') AS club_names,
            e.title,
            e.description,
            e.event_date AS date, e.event_time AS time,
            e.location,
            (SELECT COUNT(*) FROM register r WHERE r.event_id = e.id) as attendees
         FROM events e  
         JOIN club_events ce ON e.id = ce.event_id
         JOIN clubs c ON ce.club_id = c.id
         GROUP BY e.id
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

    $allEvents = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (Throwable $e) {
    $queryError = true;
}

$registeredIds = array_map('intval', $res2);

foreach ($allEvents as &$evt) {
    $evt['is_registered'] = in_array((int)$evt['id'], $registeredIds, true);
}
unset($evt);

$events = $allEvents;
if ($selectedClub !== 'all') {
    $events = array_values(array_filter(
        $allEvents,
        static function (array $event) use ($selectedClub): bool {
            $slugs = array_map('trim', explode(',', strtolower((string)($event['clubs'] ?? ''))));
            return in_array($selectedClub, $slugs, true);
        }
    ));
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


$eventsPayload = [
    'selectedClub' => $selectedClub,
    'queryError' => $queryError,
    'events' => $allEvents,
];
?>
<?php require_once ROOT_PATH . '/views/header.php' ?>


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
                            $slugs = explode(',', (string)($event['clubs'] ?? ''));
                            $cnames = explode(',', (string)($event['club_names'] ?? ''));

                            $eventDate = strtotime((string)($event['date'] ?? ''));
                            $day = $eventDate !== false ? date('j', $eventDate) : '--';
                            $month = $eventDate !== false ? strtoupper(date('M', $eventDate)) : 'TBA';
                            ?>
                            <div class="event-card" data-club="<?php echo escapeText(implode(',', array_map('trim', $slugs))); ?>">
                                <div class="date-badge">
                                    <div class="day-badge"><?php echo escapeText((string)$day); ?></div>
                                    <div class="month-badge"><?php echo escapeText($month); ?></div>
                                </div>

                                <div class="event-content">
                                    <div class="event-header">
                                        <div class="club-tags" style="display:flex; gap:6px; flex-wrap:wrap;">
                                            <?php foreach ($slugs as $i => $s):
                                                $s = trim($s);
                                                $color = $clubColors[$s] ?? '#3182ce';
                                                $label = $clubNames[$s] ?? strtoupper($s);
                                            ?>
                                                <div class="club-tag <?php echo escapeText($s); ?>"
                                                    style="background-color: <?php echo escapeText($color) ?>">
                                                    <?php echo escapeText($label); ?>
                                                </div>
                                            <?php endforeach; ?>
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
                                            <span><?php echo (int)($event['attendees'] ?? 0); ?> attendees</span>
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
<?php require_once ROOT_PATH . '/views/footer.php'; ?>