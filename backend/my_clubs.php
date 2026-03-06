<?php
// ============================================================
// my_clubs.php – Get list of clubs joined by the logged-in user
// ============================================================

session_start();
require_once __DIR__ . '/cors.php';

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'not_logged_in']);
    exit;
}

require_once __DIR__ . '/db.php';
$userId = (int)$_SESSION['user_id'];
$db = getDB();

try {
    // Join memberships with clubs to get club details
    $stmt = $db->prepare('
        SELECT c.name, c.slug, m.joined_at 
        FROM memberships m
        JOIN clubs c ON m.club_id = c.id
        WHERE m.user_id = ?
        ORDER BY m.joined_at DESC
    ');
    $stmt->execute([$userId]);
    $clubs = $stmt->fetchAll();

    echo json_encode(['success' => true, 'clubs' => $clubs]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
