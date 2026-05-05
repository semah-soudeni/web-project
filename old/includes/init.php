<?php

define('ROOT_PATH', dirname(__DIR__));
define('BASE_URL', '/web-project/');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once ROOT_PATH . '/backend/bd.php';

$isLoggedIn = isset($_SESSION['logged']) && $_SESSION['logged'] === 'yes';
$displayName = '';

if ($isLoggedIn) {
    $firstName = trim((string)($_SESSION['user_first_name'] ?? ''));
    $lastName = trim((string)($_SESSION['user_last_name'] ?? ''));
    $displayName = trim("$firstName $lastName" ?: $_SESSION['user_email'] ?? 'User');
}

function isLoggedIn(): bool
{
    return isset($_SESSION['logged']) && $_SESSION['logged'] === 'yes';
}

function isAdmin(): bool
{
    return isLoggedIn() && ($_SESSION['role'] ?? '') === 'admin';
}

function requireLogin(string $redirect = BASE_URL . '/pages/signin.php'): void
{
    if (!isLoggedIn()) {
        header("Location: $redirect");
        exit;
    }
}

function requireAdmin(string $redirect = BASE_URL . 'pages/error.php?code=401'): void
{
    if (!isAdmin()) {
        header("Location: $redirect");
        exit;
    }
}

function flashSet(string $key, string $msg): void
{
    $_SESSION['flash'][$key] = $msg;
}

function flashGet(string $key): string
{
    $msg = $_SESSION['flash'][$key] ?? '';
    unset($_SESSION['flash'][$key]);
    return $msg;
}

function escapeText(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
