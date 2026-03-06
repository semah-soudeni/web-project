<?php
// ============================================================
// logout.php – Destroy session and redirect
// ============================================================

session_start();
session_unset();
session_destroy();

require_once __DIR__ . '/cors.php';
header('Content-Type: application/json');
echo json_encode(['success' => true]);
