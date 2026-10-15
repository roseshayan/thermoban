<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Order extends Model {
    protected static string $table = 'orders';

    public static function getUserOrders(int $userId): array {
        $orders = Database::fetchAll(
            "SELECT o.*, ua.title as address_title, ua.district as address_district, ua.address as address_text 
             FROM `orders` o
             LEFT JOIN `user_addresses` ua ON o.address_id = ua.id
             WHERE o.user_id = :user_id 
             ORDER BY o.id DESC",
            ['user_id' => $userId]
        );

        foreach ($orders as &$order) {
            $order['items'] = Database::fetchAll(
                "SELECT oi.*, p.image, p.model 
                 FROM `order_items` oi
                 LEFT JOIN `products` p ON oi.product_id = p.id
                 WHERE oi.order_id = :order_id",
                ['order_id' => $order['id']]
            );
        }

        return $orders;
    }

    public static function findByOrderNumber(string $orderNumber): ?array {
        $order = Database::fetchOne(
            "SELECT o.*, u.name as user_name, u.phone as user_phone, u.national_id, 
                    ua.title as address_title, ua.address as address_text, ua.postal_code, ua.phone as receiver_phone
             FROM `orders` o
             LEFT JOIN `users` u ON o.user_id = u.id
             LEFT JOIN `user_addresses` ua ON o.address_id = ua.id
             WHERE o.order_number = :ord LIMIT 1",
            ['ord' => $orderNumber]
        );

        if ($order) {
            $order['items'] = Database::fetchAll(
                "SELECT oi.*, p.image, p.model 
                 FROM `order_items` oi
                 LEFT JOIN `products` p ON oi.product_id = p.id
                 WHERE oi.order_id = :order_id",
                ['order_id' => $order['id']]
            );
        }

        return $order;
    }
}
