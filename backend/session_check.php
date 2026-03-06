<?php
// ============================================================
// session_check.php – Return current login state as JSON
// Called by JS on every page load.
// Returns:
//   { loggedIn: false }
//   { loggedIn: true, user: { first_name, last_name, email } }
// ============================================================

session_start();
require_once __DIR__ . '/cors.php';
header('Content-Type: application/json');

if (!empty($_SESSION['user_id'])) {
    echo json_encode([
        'loggedIn' => true,
        'user'     => [
            'first_name' => $_SESSION['first_name'],
            'last_name'  => $_SESSION['last_name'],
            'email'      => $_SESSION['email'],
        ]
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
