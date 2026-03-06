<?php
// ============================================================
// login.php – Sign-in endpoint
// Accepts POST (JSON or form-encoded): email, password
// Optional GET param: ?redirect=  (URL to go to after login)
// Returns JSON: { success: true, user: {...} } or { error: "..." }
// ============================================================

session_start();
require_once __DIR__ . '/cors.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/db.php';

// Accept JSON body or regular form POST
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data) $data = $_POST;

$email    = trim($data['email']    ?? '');
$password = $data['password'] ?? '';

// ── Basic validation ─────────────────────────────────────────
if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Email and password are required.']);
    exit;
}

$db   = getDB();
$stmt = $db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Incorrect email or password.']);
    exit;
}

// ── Start session ────────────────────────────────────────────
session_regenerate_id(true);
$_SESSION['user_id']    = $user['id'];
$_SESSION['first_name'] = $user['first_name'];
$_SESSION['last_name']  = $user['last_name'];
$_SESSION['email']      = $user['email'];

echo json_encode([
    'success' => true,
    'user'    => [
        'first_name' => $user['first_name'],
        'last_name'  => $user['last_name'],
        'email'      => $user['email'],
    ]
]);
