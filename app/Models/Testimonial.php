<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\Services\DatabaseMigrationService;

class Testimonial extends Model {
    protected static string $table = 'testimonials';

    /**
     * Get all active testimonials ordered by sort_order
     */
    public static function getActive(int $limit = 10): array {
        DatabaseMigrationService::ensureTable('testimonials');
        return static::where("`is_active` = 1", [], "`sort_order` ASC, `id` DESC", $limit);
    }
}
