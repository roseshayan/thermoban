<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class VisitorLog extends Model {
    protected static string $table = 'visitor_logs';

    /**
     * Record a new visit
     */
    public static function track(): void {
        if (Setting::get('module_analytics', '1') !== '1') {
            return;
        }

        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Skip assets, api, admin calls
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|woff|woff2|ico|ttf)$/i', $uri)) {
            return;
        }
        if (str_starts_with($uri, '/admin') || str_starts_with($uri, '/api')) {
            return;
        }

        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        if (str_contains($ip, ',')) {
            $ip = trim(explode(',', $ip)[0]);
        }

        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $device = 'desktop';
        if (preg_match('/(android|iphone|ipad|ipod|mobile)/i', $ua)) {
            $device = preg_match('/(tablet|ipad)/i', $ua) ? 'tablet' : 'mobile';
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? null;

        try {
            self::create([
                'ip_address'  => substr($ip, 0, 45),
                'page_url'    => substr($uri, 0, 255),
                'referer'     => $referer ? substr($referer, 0, 255) : null,
                'user_agent'  => substr($ua, 0, 255),
                'device_type' => $device
            ]);
        } catch (\Throwable $e) {
            // Silently ignore tracking errors so they never interrupt normal page loading
        }
    }

    /**
     * Get analytics summary stats
     */
    public static function getStats(): array {
        $today = date('Y-m-d');
        $thisWeek = date('Y-m-d', strtotime('-7 days'));
        $thisMonth = date('Y-m-d', strtotime('-30 days'));

        $totalVisits = Database::fetchOne("SELECT COUNT(*) as c FROM `visitor_logs`")['c'] ?? 0;
        $todayVisits = Database::fetchOne("SELECT COUNT(*) as c FROM `visitor_logs` WHERE DATE(`created_at`) = :d", ['d' => $today])['c'] ?? 0;
        $todayUnique = Database::fetchOne("SELECT COUNT(DISTINCT `ip_address`) as c FROM `visitor_logs` WHERE DATE(`created_at`) = :d", ['d' => $today])['c'] ?? 0;
        $weekVisits  = Database::fetchOne("SELECT COUNT(*) as c FROM `visitor_logs` WHERE `created_at` >= :w", ['w' => $thisWeek])['c'] ?? 0;
        
        $devices = Database::fetchAll("SELECT `device_type`, COUNT(*) as cnt FROM `visitor_logs` GROUP BY `device_type`");
        $topPages = Database::fetchAll("SELECT `page_url`, COUNT(*) as hits FROM `visitor_logs` GROUP BY `page_url` ORDER BY hits DESC LIMIT 6");

        return [
            'total_visits' => (int) $totalVisits,
            'today_visits' => (int) $todayVisits,
            'today_unique' => (int) $todayUnique,
            'week_visits'  => (int) $weekVisits,
            'devices'      => $devices,
            'top_pages'    => $topPages
        ];
    }
}
