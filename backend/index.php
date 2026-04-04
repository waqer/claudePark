<?php
declare(strict_types=1);

// ── Response headers ─────────────────────────────────────────────────────────
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle CORS pre-flight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only GET requests are served
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/routes.php';

// ── Router ───────────────────────────────────────────────────────────────────
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

// Strip subfolder prefix so the router works under any document root path
// e.g. /claudePark/backend/api/zones → /api/zones
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($scriptDir !== '' && str_starts_with($uri, $scriptDir)) {
    $uri = substr($uri, strlen($scriptDir));
}
if ($uri === '') $uri = '/';

// ── GET /api/zones ────────────────────────────────────────────────────────────
if ($uri === '/api/zones') {
    http_response_code(200);
    echo json_encode(get_zones(), JSON_PRETTY_PRINT);
    exit;
}

// ── GET /api/zones/{id} ───────────────────────────────────────────────────────
if (preg_match('#^/api/zones/(\d+)$#', $uri, $matches)) {
    $id   = (int) $matches[1];
    $zone = get_zone($id);

    if ($zone === null) {
        http_response_code(404);
        echo json_encode(['error' => "Zone with id {$id} not found."]);
    } else {
        http_response_code(200);
        echo json_encode($zone, JSON_PRETTY_PRINT);
    }
    exit;
}

// ── 404 fallback ──────────────────────────────────────────────────────────────
http_response_code(404);
echo json_encode(['error' => 'Endpoint not found.']);
