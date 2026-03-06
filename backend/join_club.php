<?php
// ============================================================
// join_club.php – Add the logged-in user to a club
// Accepts POST (JSON or form-encoded): club_slug
// Returns JSON:
//   { success: true }
//   { success: true, already_member: true }
//   { error: "not_logged_in" }
//   { error: "..." }
// ============================================================

session_start();
require_once __DIR__ . '/cors.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// ── Auth check ───────────────────────────────────────────────
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'not_logged_in']);
    exit;
}

require_once __DIR__ . '/db.php';

// Accept JSON body or form POST
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data) $data = $_POST;

$clubSlug = trim($data['club_slug'] ?? '');
$userId   = (int) $_SESSION['user_id'];

if (!$clubSlug) {
    http_response_code(400);
    echo json_encode(['error' => 'Club not specified.']);
    exit;
}

$db = getDB();

// ── Validate the club exists ─────────────────────────────────
$stmt = $db->prepare('SELECT id FROM clubs WHERE slug = ? LIMIT 1');
$stmt->execute([$clubSlug]);
$club = $stmt->fetch();

if (!$club) {
    http_response_code(404);
    echo json_encode(['error' => 'Club not found.']);
    exit;
}
$clubId = (int)$club['id'];

// ── Check if already a member ────────────────────────────────
$stmt = $db->prepare('SELECT 1 FROM memberships WHERE user_id = ? AND club_id = ? LIMIT 1');
$stmt->execute([$userId, $clubId]);
if ($stmt->fetch()) {
    echo json_encode(['success' => true, 'already_member' => true]);
    exit;
}

// ── Insert membership ────────────────────────────────────────
$stmt = $db->prepare('INSERT INTO memberships (user_id, club_id) VALUES (?, ?)');
$stmt->execute([$userId, $clubId]);

echo json_encode(['success' => true, 'already_member' => false]);
