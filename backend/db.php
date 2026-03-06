<?php
// ============================================================
// db.php – PDO connection singleton
// Edit DB_HOST / DB_NAME / DB_USER / DB_PASS to match your
// XAMPP / server settings.
// ============================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'insat_clubs');
define('DB_USER', 'root');        // default XAMPP user
define('DB_PASS', 'amine01112005');            // default XAMPP password (empty)
define('DB_CHARSET', 'utf8mb4');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
            exit;
        }
    }
    return $pdo;
}
