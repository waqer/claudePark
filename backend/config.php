<?php
declare(strict_types=1);

// ── Load .env file ────────────────────────────────────────────────────────────
// Looks one directory up from backend/ so the .env sits at project root.
// Safe to call even if .env doesn't exist.
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        [$key, $value] = explode('=', $line, 2);
        // putenv only — don't overwrite vars already set by docker-compose
        if (getenv(trim($key)) === false) {
            putenv(trim($key) . '=' . trim($value));
        }
    }
}

define('API_BASE_URL', getenv('API_BASE_URL') ?: 'http://localhost:8080/api');

// ── Database ──────────────────────────────────────────────────────────────────
define('DB_HOST', getenv('DB_HOST') ?: 'db');        // 'db' = Docker service name
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'parkman');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: 'secret');

// ── PDO connection ────────────────────────────────────────────────────────────
function get_db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        DB_HOST, DB_PORT, DB_NAME
    );

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'error'   => 'Database connection failed',
            'message' => $e->getMessage(),
        ]);
        exit;
    }

    return $pdo;
}