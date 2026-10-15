<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Comment extends Model {
    protected static string $table = 'comments';

    public static function forProduct(int $productId): array {
        return Database::fetchAll(
            "SELECT * FROM `comments` WHERE `product_id` = :pid AND `status` = 'approved' ORDER BY `id` DESC",
            ['pid' => $productId]
        );
    }

    public static function forArticle(int $articleId): array {
        return Database::fetchAll(
            "SELECT * FROM `comments` WHERE `article_id` = :aid AND `status` = 'approved' ORDER BY `id` DESC",
            ['aid' => $articleId]
        );
    }
}
