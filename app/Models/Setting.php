<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Setting extends Model {
    protected static string $table = 'settings';

    public static function get(string $key, ?string $default = null): ?string {
        $row = Database::fetchOne("SELECT `setting_value` FROM `settings` WHERE `setting_key` = :key LIMIT 1", ['key' => $key]);
        return $row ? $row['setting_value'] : $default;
    }

    public static function set(string $key, ?string $value): void {
        $exists = self::get($key) !== null;
        if ($exists) {
            Database::update('settings', ['setting_value' => $value], '`setting_key` = :k', ['k' => $key]);
        } else {
            Database::insert('settings', ['setting_key' => $key, 'setting_value' => $value]);
        }
    }

    public static function allKeyVal(): array {
        $rows = Database::fetchAll("SELECT * FROM `settings`");
        $map = [];
        foreach ($rows as $r) {
            $map[$r['setting_key']] = $r['setting_value'];
        }
        return $map;
    }
}
