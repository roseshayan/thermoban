<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\Services\DatabaseMigrationService;

class Menu extends Model {
    protected static string $table = 'menus';

    /**
     * Get menu tree (parent items with children and mega menu columns)
     */
    public static function getTree(string $location = 'header'): array {
        DatabaseMigrationService::ensureTable('menus');

        $rows = static::where("`location` = :loc AND `is_active` = 1", ['loc' => $location], "`sort_order` ASC, `id` ASC");
        
        $parents = [];
        $children = [];

        foreach ($rows as $r) {
            if (!empty($r['megamenu_data'])) {
                $r['megamenu_parsed'] = is_string($r['megamenu_data']) ? json_decode($r['megamenu_data'], true) : $r['megamenu_data'];
            } else {
                $r['megamenu_parsed'] = null;
            }

            if (empty($r['parent_id'])) {
                $parents[$r['id']] = $r;
                $parents[$r['id']]['children'] = [];
            } else {
                $children[$r['parent_id']][] = $r;
            }
        }

        foreach ($children as $parentId => $subItems) {
            if (isset($parents[$parentId])) {
                $parents[$parentId]['children'] = $subItems;
            }
        }

        return array_values($parents);
    }

    /**
     * Get all items flat for admin management
     */
    public static function getAllFlat(string $location = 'header'): array {
        DatabaseMigrationService::ensureTable('menus');
        $sql = "SELECT m.*, p.title as parent_title 
                FROM `menus` m 
                LEFT JOIN `menus` p ON m.parent_id = p.id 
                WHERE m.location = :loc 
                ORDER BY m.sort_order ASC, m.id ASC";
        return Database::fetchAll($sql, ['loc' => $location]);
    }

    /**
     * Get top-level parent items for select dropdowns
     */
    public static function getParents(string $location = 'header'): array {
        DatabaseMigrationService::ensureTable('menus');
        return static::where("`location` = :loc AND `parent_id` IS NULL", ['loc' => $location], "`sort_order` ASC, `id` ASC");
    }
}
