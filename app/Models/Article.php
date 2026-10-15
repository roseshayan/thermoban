<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Article extends Model {
    protected static string $table = 'articles';

    public static function findBySlug(string $slug): ?array {
        return Database::fetchOne("SELECT * FROM `articles` WHERE `slug` = :slug AND `is_published` = 1 LIMIT 1", ['slug' => $slug]);
    }

    public static function incrementViews(int $id): void {
        Database::query("UPDATE `articles` SET `views_count` = `views_count` + 1 WHERE `id` = :id", ['id' => $id]);
    }
}
