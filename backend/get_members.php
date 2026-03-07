<?php
// ============================================================
// get_members.php – Fetch students registered for a specific club
// Restricted to: President, VP, Secretary, Treasurer, Admin
// ============================================================

session_start();
require_once __DIR__ . '/cors.php';
header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$role = $_SESSION['role'] ?? 'member';
$clubId = $_SESSION['club_id'] ?? null;

// Permission check
$allowedRoles = ['president', 'vp', 'secretary', 'treasurer', 'admin'];
if (!in_array($role, $allowedRoles) || !$clubId) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden: You do not have permission to view this roster.']);
    exit;
}

require_once __DIR__ . '/db.php';
$db = getDB();

try {
    // Join memberships with users to get student details for the admin's club
    $stmt = $db->prepare("
        SELECT u.id as user_id, u.first_name, u.last_name, u.email, u.phone, m.joined_at, m.role
        FROM memberships m
        JOIN users u ON m.user_id = u.id
        WHERE m.club_id = ?
        ORDER BY m.joined_at DESC
    ");
    $stmt->execute([$clubId]);
    $members = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'members' => $members
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
