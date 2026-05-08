<?php
require_once __DIR__ . '/../includes/init.php';

requireLogin();

$pageTitle  = 'Event Registration';
$activePage = 'events';
$extraCss   = [BASE_URL . 'assets/css/register.css'];

$event_id = (int)($_GET['event_id'] ?? 0);

if (!$event_id) {
    header('Location: ' . BASE_URL . 'pages/events.php');
    exit;
}

$event = null;
$queryError = false;

try {
    $conn = ConnexionBD::getInstance();

    $stmt = $conn->prepare("
        SELECT e.id, e.title, e.description, e.event_date, e.event_time, e.location,
               e.max_attendees,
               (SELECT COUNT(*) FROM register r WHERE r.event_id = e.id) as attendees
        FROM events e
        WHERE e.id = ?
    ");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        header('Location: ' . BASE_URL . 'pages/events.php');
        exit;
    }

    $stmtCheck = $conn->prepare("
        SELECT id FROM register 
        WHERE user_id = ? AND event_id = ?
    ");
    $stmtCheck->execute([$_SESSION['id'], $event_id]);
    $alreadyRegistered = $stmtCheck->fetch();

    if ($alreadyRegistered) {
        header('Location: ' . BASE_URL . 'pages/events.php');
        exit;
    }

    if ($event['max_attendees'] !== null && $event['attendees'] >= $event['max_attendees']) {
        $eventFull = true;
    }

} catch (Throwable $e) {
    $queryError = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$queryError) {
    try {
        $teamName    = trim($_POST['team_name'] ?? '');
        $teamMembers = (int)($_POST['team_nb_memebers'] ?? 0);
        $links       = trim($_POST['links'] ?? '');

        $stmt = $conn->prepare("
            INSERT INTO register (user_id, event_id, paid, team_name, team_nb_memebers, links)
            VALUES (:user_id, :event_id, :paid, :team_name, :team_nb_memebers, :links)
        ");

        $stmt->execute([
            ':user_id'         => $_SESSION['id'],
            ':event_id'        => $event_id,
            ':paid'            => false,
            ':team_name'       => $teamName ?: null,
            ':team_nb_memebers'=> $teamMembers ?: null,
            ':links'           => $links ?: null,
        ]);

        header('Location: ' . BASE_URL . 'pages/events.php');
        exit;

    } catch (Throwable $e) {
        $registerError = 'Registration failed. You may already be registered.';
    }
}

require_once ROOT_PATH . '/views/header.php';
?>

<div class="reg-page">
    <div class="reg-card">

        <?php if ($queryError): ?>
            <h1>Error</h1>
            <p>Could not load event. Please try again.</p>
            <a href="<?= BASE_URL ?>pages/events.php" class="reg-btn-ghost">Back to Events</a>

        <?php else: ?>
            <h1>Register for <?= escapeText($event['title']) ?></h1>
            <p class="reg-subtitle">Fill in the details below to complete your registration.</p>

            <?php if (!empty($registerError)): ?>
                <div class="alert-error"><?= escapeText($registerError) ?></div>
            <?php endif; ?>

            <?php if (!empty($eventFull)): ?>
                <div class="alert-error">This event is full.</div>
            <?php else: ?>
                <form class="reg-form" action="" method="POST">

                    <div class="reg-field">
                        <label for="team_name">Team Name <span class="optional">(optional)</span></label>
                        <input type="text" id="team_name" name="team_name"
                               maxlength="300" placeholder="e.g. The Bug Hunters">
                    </div>

                    <div class="reg-field">
                        <label for="team_nb_memebers">Number of Team Members <span class="optional">(optional)</span></label>
                        <input type="number" id="team_nb_memebers" name="team_nb_memebers"
                               min="1" max="20" placeholder="e.g. 3">
                    </div>

                    <div class="reg-field">
                        <label for="links">Links <span class="optional">(optional)</span></label>
                        <textarea id="links" name="links" maxlength="1000"
                                  placeholder="Portfolio, GitHub, LinkedIn…"></textarea>
                        <p class="reg-hint">Paste one or more links, one per line.</p>
                    </div>

                    <div class="reg-actions">
                        <button type="submit" class="reg-btn-primary">
                            ✓ Register
                        </button>
                        <a class="reg-btn-ghost" href="<?= BASE_URL ?>pages/events.php">
                            ← Back to Events
                        </a>
                    </div>

                </form>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>

<?php require_once ROOT_PATH . '/views/footer.php'; ?>