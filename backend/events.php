<?php
require_once "bd.php";

header('Content-Type: application/json');

$club = $_GET['club'] ?? 'all';
$conn = ConnexionBD::getInstance();

if ($club !== 'all') {
    $req = $conn->prepare("
        SELECT e.id, c.slug AS club, e.title, e.description,
        e.event_date AS date, e.event_time AS time,
        e.location, e.attendees
        FROM events e
        JOIN clubs c ON e.club_id = c.id
        WHERE c.slug = ?
        ORDER BY e.event_date
    ");
    $req->execute([$club]);
} else {
    $req = $conn->query("
        SELECT e.id, c.slug AS club, e.title, e.description,
        e.event_date AS date, e.event_time AS time,
        e.location, e.attendees
        FROM events e
        JOIN clubs c ON e.club_id = c.id
        ORDER BY e.event_date
    ");
}

echo json_encode($req->fetchAll(PDO::FETCH_ASSOC));
