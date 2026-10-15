<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class User extends Model {
    protected static string $table = 'users';

    public static function findByPhone(string $phone): ?array {
        return Database::fetchOne("SELECT * FROM `users` WHERE `phone` = :phone LIMIT 1", ['phone' => $phone]);
    }

    public static function findByEmail(string $email): ?array {
        return Database::fetchOne("SELECT * FROM `users` WHERE `email` = :email LIMIT 1", ['email' => $email]);
    }
}
