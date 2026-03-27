<?php

session_start();
require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$db = getDB();

if ($method === 'GET') {
    $slug = $_GET['club'] ?? null;

    if ($slug) {
        $stmt = $db->prepare('SELECT id FROM clubs WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        $club = $stmt->fetch();

        if (!$club) {
            http_response_code(404);
            echo json_encode(['error' => 'Club not found.']);
            exit;
        }

        $stmt = $db->prepare('
            SELECT e.*, c.name AS club_name, c.slug AS club_slug
            FROM events e
            JOIN clubs c ON c.id = e.club_id
            WHERE e.club_id = ?
            ORDER BY e.event_date, e.event_time
        ');
        $stmt->execute([$club['id']]);
    } else {
        $stmt = $db->query('
            SELECT e.*, c.name AS club_name, c.slug AS club_slug
            FROM events e
            JOIN clubs c ON c.id = e.club_id
            ORDER BY e.event_date, e.event_time
        ');
    }

    echo json_encode($stmt->fetchAll());
    exit;
}

if ($method === 'POST') {
    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Not logged in.']);
        exit;
    }

    $role = $_SESSION['role'];
    $clubId = $_SESSION['club_id'] ?? null;

    if (!in_array($role, ['president', 'admin']) || !$club_id) {
        http_response_code(403);
        echo json_encode(['error' => 'Only a club president can create events.']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;

    $title = trim($data['title'] ?? '');
    $eventDate = trim($data['event_date'] ?? '');
    $description = trim($data['description' ?? '']);
    $eventTime = trim($data['event_time'] ?? '') ?: null;
    $location = trim($data['location'] ?? '') ?: null;

    if (!$title || !$eventDate) {
        http_response_code(400);
        echo json_encode(['error' => 'Title and date are required.']);
        exit;
    }

    $stmt = $db->prepare('
        INSERT INTO events(club_id, title, description, event_date, event_time, location) 
        VALUES(?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([$club_id, $title, $description, $eventDate, $eventTime, $location]);

    echo json_encode(['success' => true, 'id' => (int) $db->lastInsertId()]);
    exit;
}

if ($method === 'DELETE') {
    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Not logged in.']);
        exit;
    }

    $role = $_SESSION['role'];
    $clubId = $_SESSION['club_id'] ?? null;

    if (!in_array($role, ['president', 'admin']) || !$club_id) {
        http_response_code(403);
        echo json_encode(['error' => 'Only a club president can delete events.']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    $eventId = (int)($data['id'] ?? 0);

    if (!$eventId) {
        http_response_code(400);
        echo json_encode(['error' => 'Event ID required.']);
        exit;
    }

    $stmt = $db->prepare('DELETE FROM events WHERE id = ? AND club_id = ?');
    $stmt->execute([$eventId, $clubId]);

    echo json_encode(['success' => true]);
    exit;
}

http_response_code(500);
echo json_encode(['error' => 'Method not allowed.']);