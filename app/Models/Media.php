<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Media extends Model {
    protected static string $table = 'media';

    public static function getRecent(int $limit = 50): array {
        return Database::fetchAll("SELECT * FROM `media` ORDER BY `id` DESC LIMIT {$limit}");
    }
}
