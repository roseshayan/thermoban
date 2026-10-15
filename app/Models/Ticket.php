<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Ticket extends Model {
    protected static string $table = 'tickets';

    public static function getUserTickets(int $userId): array {
        $tickets = Database::fetchAll(
            "SELECT * FROM `tickets` WHERE `user_id` = :user_id ORDER BY `id` DESC",
            ['user_id' => $userId]
        );

        foreach ($tickets as &$t) {
            $t['messages'] = Database::fetchAll(
                "SELECT * FROM `ticket_messages` WHERE `ticket_id` = :t_id ORDER BY `id` ASC",
                ['t_id' => $t['id']]
            );
        }

        return $tickets;
    }

    public static function addMessage(int $ticketId, string $senderType, string $senderName, string $message): int {
        return Database::insert('ticket_messages', [
            'ticket_id'   => $ticketId,
            'sender_type' => $senderType,
            'sender_name' => $senderName,
            'message'     => $message,
            'created_at'  => date('Y-m-d H:i:s')
        ]);
    }
}
