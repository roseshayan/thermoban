<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class UserAddress extends Model {
    protected static string $table = 'user_addresses';

    /**
     * Get all addresses with user details
     */
    public static function getAllWithUser(string $search = '', ?int $userId = null): array {
        $sql = "SELECT ua.*, u.name as user_name, u.phone as user_account_phone, u.role as user_role
                FROM `user_addresses` ua
                LEFT JOIN `users` u ON ua.user_id = u.id
                WHERE 1=1";
        $params = [];

        if ($userId !== null) {
            $sql .= " AND ua.user_id = :uid";
            $params['uid'] = $userId;
        }

        if (!empty($search)) {
            $sql .= " AND (ua.title LIKE :q OR ua.district LIKE :q OR ua.address LIKE :q OR ua.phone LIKE :q OR u.name LIKE :q OR u.phone LIKE :q)";
            $params['q'] = "%{$search}%";
        }

        $sql .= " ORDER BY ua.id DESC";

        return Database::fetchAll($sql, $params);
    }

    /**
     * Get user addresses list
     */
    public static function getByUser(int $userId): array {
        return Database::fetchAll(
            "SELECT * FROM `user_addresses` WHERE `user_id` = :uid ORDER BY `is_default` DESC, `id` DESC",
            ['uid' => $userId]
        );
    }
}
