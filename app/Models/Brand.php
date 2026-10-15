<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Brand extends Model {
    protected static string $table = 'brands';

    public static function getActive(): array {
        return Database::fetchAll("SELECT * FROM `brands` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` ASC");
    }
}
