<?php
namespace App\Services;

use App\Core\Database;
use App\Core\Jalali;

class NotificationService {
    /**
     * Get real-time unread alert counts and recent items for Admin Topbar
     */
    public static function getAdminAlerts(): array {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $lastRead = $_SESSION['admin_alerts_last_read_at'] ?? '2000-01-01 00:00:00';

            $openTickets = (int)(Database::fetchOne(
                "SELECT COUNT(*) as c FROM `tickets` WHERE `status` = 'open' AND `created_at` > :lr",
                ['lr' => $lastRead]
            )['c'] ?? 0);

            $unreadContact = (int)(Database::fetchOne(
                "SELECT COUNT(*) as c FROM `contact_messages` WHERE `status` = 'unread' AND `created_at` > :lr",
                ['lr' => $lastRead]
            )['c'] ?? 0);

            $pendingLeads = (int)(Database::fetchOne(
                "SELECT COUNT(*) as c FROM `quick_orders` WHERE `status` = 'pending' AND `created_at` > :lr",
                ['lr' => $lastRead]
            )['c'] ?? 0);

            $pendingComments = (int)(Database::fetchOne(
                "SELECT COUNT(*) as c FROM `comments` WHERE `status` = 'pending' AND `created_at` > :lr",
                ['lr' => $lastRead]
            )['c'] ?? 0);

            $pendingServices = 0;
            try {
                $pendingServices = (int)(Database::fetchOne(
                    "SELECT COUNT(*) as c FROM `service_requests` WHERE `status` = 'pending' AND `created_at` > :lr",
                    ['lr' => $lastRead]
                )['c'] ?? 0);
            } catch (\Throwable $e) {}

            $totalLeads = $unreadContact + $pendingLeads;
            $totalAlerts = $openTickets + $totalLeads + $pendingComments + $pendingServices;

            // Fetch top recent unread/recent alerts for quick preview
            $recent = [];

            // Recent Service Requests
            try {
                $services = Database::fetchAll(
                    "SELECT id, tracking_code, customer_name, service_type, created_at, status FROM `service_requests` WHERE `status` = 'pending' ORDER BY id DESC LIMIT 3"
                );
                foreach ($services as $srv) {
                    $recent[] = [
                        'type'     => 'service',
                        'title'    => 'سفارش خدمات: ' . $srv['customer_name'] . ' (' . $srv['service_type'] . ')',
                        'time'     => Jalali::timeAgo($srv['created_at']),
                        'url'      => url('admin/service-requests'),
                        'icon'     => 'screwdriver-wrench',
                        'color'    => '#EF5C16',
                        'badge'    => 'سفارش خدمات',
                        'is_new'   => ($srv['created_at'] > $lastRead)
                    ];
                }
            } catch (\Throwable $e) {}


            // Recent tickets
            $tickets = Database::fetchAll(
                "SELECT id, ticket_number, subject, created_at FROM `tickets` WHERE `status` = 'open' ORDER BY id DESC LIMIT 3"
            );
            foreach ($tickets as $t) {
                $recent[] = [
                    'type'     => 'ticket',
                    'title'    => 'تیکت جدید: ' . ($t['subject'] ?: $t['ticket_number']),
                    'time'     => Jalali::timeAgo($t['created_at']),
                    'url'      => url('admin/tickets'),
                    'icon'     => 'headset',
                    'color'    => '#3b82f6',
                    'badge'    => 'تیکت',
                    'is_new'   => ($t['created_at'] > $lastRead)
                ];
            }

            // Recent contact messages
            $contacts = Database::fetchAll(
                "SELECT id, name, service, created_at, status FROM `contact_messages` ORDER BY id DESC LIMIT 3"
            );
            foreach ($contacts as $c) {
                $recent[] = [
                    'type'     => 'contact',
                    'title'    => 'پیام تماس از ' . $c['name'],
                    'time'     => Jalali::timeAgo($c['created_at']),
                    'url'      => url('admin/leads'),
                    'icon'     => 'paper-plane',
                    'color'    => '#10b981',
                    'badge'    => 'تماس با ما',
                    'is_new'   => ($c['status'] === 'unread' && $c['created_at'] > $lastRead)
                ];
            }

            // Recent pending comments
            $comments = Database::fetchAll(
                "SELECT id, author_name, created_at, status FROM `comments` ORDER BY id DESC LIMIT 3"
            );
            foreach ($comments as $cm) {
                $recent[] = [
                    'type'     => 'comment',
                    'title'    => 'دیدگاه از ' . $cm['author_name'],
                    'time'     => Jalali::timeAgo($cm['created_at']),
                    'url'      => url('admin/comments'),
                    'icon'     => 'comments',
                    'color'    => '#f59e0b',
                    'badge'    => 'دیدگاه',
                    'is_new'   => ($cm['status'] === 'pending' && $cm['created_at'] > $lastRead)
                ];
            }

            return [
                'total'            => $totalAlerts,
                'open_tickets'     => $openTickets,
                'unread_leads'     => $totalLeads,
                'unread_contact'   => $unreadContact,
                'pending_leads'    => $pendingLeads,
                'pending_comments' => $pendingComments,
                'recent'           => $recent
            ];
        } catch (\Throwable $e) {
            return [
                'total'            => 0,
                'open_tickets'     => 0,
                'unread_leads'     => 0,
                'unread_contact'   => 0,
                'pending_leads'    => 0,
                'pending_comments' => 0,
                'recent'           => []
            ];
        }
    }

    /**
     * Mark all active notifications as read
     */
    public static function markAllAsRead(): bool {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            Database::query("UPDATE `contact_messages` SET `status` = 'read' WHERE `status` = 'unread'");
            $_SESSION['admin_alerts_last_read_at'] = date('Y-m-d H:i:s');
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
