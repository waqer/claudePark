<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pdo = get_db();

// Create zones table
$pdo->exec("
    CREATE TABLE IF NOT EXISTS zones (
        id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name             VARCHAR(120)   NOT NULL,
        type             ENUM('commercial','street','residential') NOT NULL,
        status           ENUM('active','limited','seasonal','inactive') NOT NULL DEFAULT 'active',
        description      TEXT           NOT NULL,
        max_capacity     SMALLINT UNSIGNED NOT NULL DEFAULT 0,
        hourly_rate_eur  DECIMAL(5,2)   NOT NULL DEFAULT 0.00,
        latitude         DECIMAL(9,6)   NOT NULL,
        longitude        DECIMAL(9,6)   NOT NULL,
        amenities        JSON           NOT NULL,
        opening_hours    JSON           NOT NULL,
        created_at       TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

echo "Migration complete: 'zones' table ready.\n";
