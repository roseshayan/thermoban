<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class ServiceTariff extends Model {
    protected static string $table = 'service_tariffs';

    /**
     * Get all active tariffs ordered by sort_order
     */
    public static function getActive(): array {
        $rows = static::where("`is_active` = 1", [], "`sort_order` ASC, `id` ASC");
        foreach ($rows as &$row) {
            if (!empty($row['features']) && is_string($row['features'])) {
                $decoded = json_decode($row['features'], true);
                $row['features_array'] = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode("\n", $row['features'])));
            } else {
                $row['features_array'] = [];
            }
        }
        return $rows;
    }

    /**
     * Find by service key
     */
    public static function findByKey(string $key): ?array {
        return static::first("`service_key` = :key", ['key' => $key]);
    }
}
