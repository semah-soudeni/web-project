<?php
// ============================================================
// update_membership.php – Change member role with business rules
// Rules: 
// 1. Must be Club Admin of the specific club
// 2. Promotion to executive roles requires 1 year tenure
// 3. One President, VPA, VPT per club
// ============================================================

session_start();
require_once __DIR__ . '/cors.php';
header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$adminRole = $_SESSION['role'] ?? 'member';
$adminClubId = $_SESSION['club_id'] ?? null;

// The president of the club can manage roles
if ($adminRole !== 'president' || !$adminClubId) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden: Only the Club President can manage roles.']);
    exit;
}

require_once __DIR__ . '/db.php';
$db = getDB();

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

$targetUserId = (int)($data['user_id'] ?? 0);
$newRole      = $data['role'] ?? 'member';

$allowedRoles = ['member', 'president', 'vpa', 'vpt'];
if (!in_array($newRole, $allowedRoles)) {
    echo json_encode(['error' => 'Invalid role selected.']);
    exit;
}

try {
    // 1. Fetch current membership
    $stmt = $db->prepare("SELECT role, joined_at FROM memberships WHERE user_id = ? AND club_id = ?");
    $stmt->execute([$targetUserId, $adminClubId]);
    $membership = $stmt->fetch();

    if (!$membership) {
        echo json_encode(['error' => 'User is not a member of this club.']);
        exit;
    }

    // 2. Tenure check for promotions (365 days)
    if ($newRole !== 'member') {
        $joinedDate = new DateTime($membership['joined_at']);
        $now = new DateTime();
        $interval = $joinedDate->diff($now);
        $days = (int)$interval->format('%a');

        if ($days < 365) {
            echo json_encode(['error' => 'Member must have been in the club for at least 1 year (365 days) to be promoted. Current tenure: ' . $days . ' days.']);
            exit;
        }
    }

    // 3. Uniqueness and Exclusivity check for executive roles
    if ($newRole !== 'member') {
        // 3a. Position uniqueness within THIS club
        $stmt = $db->prepare("SELECT 1 FROM memberships WHERE club_id = ? AND role = ? AND user_id != ?");
        $stmt->execute([$adminClubId, $newRole, $targetUserId]);
        if ($stmt->fetch()) {
            $roleLabel = ($newRole === 'president') ? 'President' : (($newRole === 'vpa') ? 'Administrative VP' : 'Technical VP');
            echo json_encode(['error' => "This club already has an active $roleLabel. There can only be one."]);
            exit;
        }

        // 3b. Role exclusivity: User cannot be an executive in any OTHER club
        $stmt = $db->prepare("SELECT c.name FROM memberships m JOIN clubs c ON m.club_id = c.id WHERE m.user_id = ? AND m.role != 'member' AND m.club_id != ?");
        $stmt->execute([$targetUserId, $adminClubId]);
        $otherClub = $stmt->fetch();
        if ($otherClub) {
            echo json_encode(['error' => "This student is already an officer in " . $otherClub['name'] . ". They can only hold one executive position across all clubs."]);
            exit;
        }
    }

    // 4. Update
    $stmt = $db->prepare("UPDATE memberships SET role = ? WHERE user_id = ? AND club_id = ?");
    $stmt->execute([$newRole, $targetUserId, $adminClubId]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
