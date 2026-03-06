<?php
/**
 * cors.php – Centralized CORS and session handling
 * 
 * In local XAMPP development, we reflect the Origin to allow credentials (cookies).
 * Access-Control-Allow-Origin cannot be '*' when Access-Control-Allow-Credentials is true.
 */

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

// For security, you could check if $origin matches 'http://localhost' or your domain.
if ($origin) {
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Credentials: true");
}

header('Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
