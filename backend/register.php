<?php
// ============================================================
// register.php – Sign-up endpoint
// Accepts POST (JSON or form-encoded):
//   first_name, last_name, email, phone, password
// Returns JSON: { success: true } or { error: "..." }
// ============================================================

session_start();
require_once __DIR__ . '/cors.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/db.php';

// Accept both JSON body and regular form POST
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data) $data = $_POST;

$firstName = trim($data['first_name']  ?? $data['firstName'] ?? '');
$lastName  = trim($data['last_name']   ?? $data['lastName']  ?? '');
$email     = trim($data['email']  ?? '');
$phone     = trim($data['phone']  ?? '');
$password  = $data['password'] ?? '';

// ── Validation ──────────────────────────────────────────────
if (!$firstName || !$lastName || !$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'All required fields must be filled.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email address.']);
    exit;
}
if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['error' => 'Password must be at least 8 characters.']);
    exit;
}

$db = getDB();

// ── Check duplicate email ────────────────────────────────────
$stmt = $db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['error' => 'An account with this email already exists.']);
    exit;
}

// ── Insert ───────────────────────────────────────────────────
$hash = password_hash($password, PASSWORD_BCRYPT);
$stmt = $db->prepare(
    'INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)'
);
$stmt->execute([$firstName, $lastName, $email, $phone ?: null, $hash]);
$userId = (int) $db->lastInsertId();

// ── Auto-login after registration ───────────────────────────
$_SESSION['user_id']    = $userId;
$_SESSION['first_name'] = $firstName;
$_SESSION['last_name']  = $lastName;
$_SESSION['email']      = $email;

echo json_encode([
    'success' => true,
    'user'    => [
        'first_name' => $firstName,
        'last_name'  => $lastName,
        'email'      => $email,
    ]
]);
