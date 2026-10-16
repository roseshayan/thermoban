<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\Services\DatabaseMigrationService;

class Branch extends Model {
    protected static string $table = 'branches';

    /**
     * Get all active branches ordered by is_main DESC, sort_order ASC
     */
    public static function getActive(): array {
        DatabaseMigrationService::ensureTable('branches');
        return static::where("`is_active` = 1", [], "`is_main` DESC, `sort_order` ASC, `id` ASC");
    }

    /**
     * Get main central branch
     */
    public static function getMainBranch(): ?array {
        DatabaseMigrationService::ensureTable('branches');
        return static::first("`is_main` = 1 AND `is_active` = 1");
    }
}
