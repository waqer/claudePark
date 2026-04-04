<?php
declare(strict_types=1);

/**
 * GET /api/zones
 *
 * Returns a summary list of all zones.
 * Only exposes: id, name, type, status.
 */
function get_zones(): array
{
    $pdo  = get_db();
    $rows = $pdo
        ->query("
            SELECT id, name, type, status
            FROM   zones
            ORDER  BY id ASC
        ")
        ->fetchAll();

    // Cast id to int for clean JSON output
    return array_map(function (array $row): array {
        $row['id'] = (int) $row['id'];
        return $row;
    }, $rows);
}

/**
 * GET /api/zones/{id}
 *
 * Returns full details for a single zone.
 * Returns null when no row is found (caller sends 404).
 */
function get_zone(int $id): ?array
{
    $pdo  = get_db();
    $stmt = $pdo->prepare("
        SELECT  id, name, type, status, description,
                max_capacity, hourly_rate_eur,
                latitude, longitude,
                amenities, opening_hours
        FROM    zones
        WHERE   id = :id
        LIMIT   1
    ");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();

    if ($row === false) return null;

    // Decode JSON columns
    $row['amenities']     = json_decode($row['amenities'],     true) ?? [];
    $row['opening_hours'] = json_decode($row['opening_hours'], true) ?? [];

    // Enforce correct PHP/JSON types
    $row['id']              = (int)   $row['id'];
    $row['max_capacity']    = (int)   $row['max_capacity'];
    $row['hourly_rate_eur'] = (float) $row['hourly_rate_eur'];
    $row['latitude']        = (float) $row['latitude'];
    $row['longitude']       = (float) $row['longitude'];

    return $row;
}
