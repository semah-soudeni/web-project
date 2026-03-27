<?php
// ============================================================
// db.php – PDO connection singleton
// Edit DB_HOST / DB_NAME / DB_USER / DB_PASS to match your
// XAMPP / server settings.
// ============================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'insat_clubs');
define('DB_USER', 'root');
define('DB_PASS', '');        
define('DB_CHARSET', 'utf8mb4');

header('Content-Type: application/json');

function getDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
            $pdo = new PDO($dsn, DB_USER, DB_PASS);

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
            exit;
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    return $pdo;
}
