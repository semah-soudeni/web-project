<?php
require_once "bd.php";

header('Content-Type: application/json');

$club = $_GET['club'] ?? 'all';
$conn = ConnexionBD::getInstance();

$query = "
    SELECT 
        e.id,
        e.title,
        e.description,
        e.event_date AS date,
        e.event_time AS time,
        e.location,
        e.event_type,
        GROUP_CONCAT(c.slug) AS clubs,
        GROUP_CONCAT(c.name) AS club_names
    FROM events e
    JOIN club_events ce ON e.id = ce.event_id
    JOIN clubs c ON ce.club_id = c.id
";

if ($club !== 'all') {
    $req = $conn->prepare("
        $query
        WHERE e.id IN (
            SELECT ce2.event_id FROM club_events ce2
            JOIN clubs c2 ON ce2.club_id = c2.id
            WHERE c2.slug = ?
        )
        GROUP BY e.id
        ORDER BY e.event_date
    ");
    $req->execute([$club]);
} else {
    $req = $conn->query("$query GROUP BY e.id ORDER BY e.event_date");
}

echo json_encode($req->fetchAll(PDO::FETCH_ASSOC));
