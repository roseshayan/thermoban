<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class ServiceRequest extends Model {
    protected static string $table = 'service_requests';

    /**
     * Find by tracking code
     */
    public static function findByTrackingCode(string $code): ?array {
        \App\Services\DatabaseMigrationService::ensureTable('service_requests');
        $code = trim($code);
        return Database::fetchOne(
            "SELECT sr.*, u.name as user_name, u.phone as user_phone, tech.name as assigned_tech_name, tech.phone as assigned_tech_phone
             FROM `service_requests` sr
             LEFT JOIN `users` u ON sr.user_id = u.id
             LEFT JOIN `users` tech ON sr.technician_id = tech.id
             WHERE sr.tracking_code = :code LIMIT 1",
            ['code' => $code]
        );
    }

    /**
     * Get all with user and technician details
     */
    public static function getAllWithDetails(string $statusFilter = '', string $search = ''): array {
        \App\Services\DatabaseMigrationService::ensureTable('service_requests');
        $sql = "SELECT sr.*, u.name as user_account_name, tech.name as tech_name, tech.phone as tech_phone
                FROM `service_requests` sr
                LEFT JOIN `users` u ON sr.user_id = u.id
                LEFT JOIN `users` tech ON sr.technician_id = tech.id
                WHERE 1=1";
        $params = [];

        if (!empty($statusFilter)) {
            $sql .= " AND sr.status = :status";
            $params['status'] = $statusFilter;
        }

        if (!empty($search)) {
            $sql .= " AND (sr.tracking_code LIKE :q OR sr.customer_name LIKE :q OR sr.customer_phone LIKE :q OR sr.district LIKE :q OR sr.brand LIKE :q)";
            $params['q'] = "%{$search}%";
        }

        $sql .= " ORDER BY sr.id DESC";

        $rows = Database::fetchAll($sql, $params);
        foreach ($rows as &$r) {
            if (!empty($r['photos'])) {
                $r['photos_array'] = is_string($r['photos']) ? json_decode($r['photos'], true) : $r['photos'];
            } else {
                $r['photos_array'] = [];
            }
        }
        return $rows;
    }

    /**
     * Generate unique tracking code (e.g. TRM-1405-72849)
     */
    public static function generateTrackingCode(): string {
        $j = \App\Core\Jalali::toJalali(time());
        $year = $j['y'];
        do {
            $rand = mt_rand(10000, 99999);
            $code = "TRM-{$year}-{$rand}";
            $exists = Database::fetchOne("SELECT `id` FROM `service_requests` WHERE `tracking_code` = :code LIMIT 1", ['code' => $code]);
        } while ($exists);

        return $code;
    }
}
