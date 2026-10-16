<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Setting extends Model {
    protected static string $table = 'settings';

    public static function get(string $key, ?string $default = null): ?string {
        try {
            $row = Database::fetchOne("SELECT `setting_value` FROM `settings` WHERE `setting_key` = :key LIMIT 1", ['key' => $key]);
            if ($row && $row['setting_value'] !== null && $row['setting_value'] !== '') {
                return (string)$row['setting_value'];
            }
            return $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function set(string $key, ?string $value): void {
        try {
            $exists = Database::fetchOne("SELECT `id` FROM `settings` WHERE `setting_key` = :key LIMIT 1", ['key' => $key]);
            if ($exists) {
                Database::update('settings', ['setting_value' => $value], '`setting_key` = :k', ['k' => $key]);
            } else {
                Database::insert('settings', ['setting_key' => $key, 'setting_value' => $value]);
            }
        } catch (\Throwable $e) {
            // Silently handle
        }
    }

    public static function allKeyVal(): array {
        try {
            $rows = Database::fetchAll("SELECT * FROM `settings`");
            $map = [];
            foreach ($rows as $r) {
                $map[$r['setting_key']] = $r['setting_value'];
            }
            return $map;
        } catch (\Throwable $e) {
            return [];
        }
    }
}
