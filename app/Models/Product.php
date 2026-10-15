<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Product extends Model {
    protected static string $table = 'products';

    /**
     * Get products with brand details
     */
    public static function getActiveWithBrand(?int $brandId = null, string $sort = 'newest'): array {
        $sql = "SELECT p.*, b.name as brand_name, b.slug as brand_slug, b.logo as brand_logo 
                FROM `products` p
                LEFT JOIN `brands` b ON p.brand_id = b.id
                WHERE p.is_active = 1";
        
        $params = [];
        if ($brandId) {
            $sql .= " AND p.brand_id = :brand_id";
            $params['brand_id'] = $brandId;
        }

        switch ($sort) {
            case 'price_asc':
                $sql .= " ORDER BY p.price ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY p.price DESC";
                break;
            case 'popular':
                $sql .= " ORDER BY p.reviews_count DESC, p.rating DESC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY p.id DESC";
                break;
        }

        return Database::fetchAll($sql, $params);
    }

    /**
     * Find single product with brand
     */
    public static function findWithBrand(int $id): ?array {
        $sql = "SELECT p.*, b.name as brand_name, b.slug as brand_slug, b.logo as brand_logo 
                FROM `products` p
                LEFT JOIN `brands` b ON p.brand_id = b.id
                WHERE p.id = :id AND p.is_active = 1 LIMIT 1";
        
        $item = Database::fetchOne($sql, ['id' => $id]);
        if ($item) {
            $item['features'] = json_decode($item['features'] ?? '[]', true) ?: [];
            $item['specs'] = json_decode($item['specs'] ?? '{}', true) ?: [];
        }
        return $item;
    }
}
