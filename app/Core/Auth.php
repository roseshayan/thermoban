<?php
namespace App\Core;

class Auth {
    /**
     * Attempt login with phone or email and password
     */
    public static function attempt(string $identifier, string $password): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = Database::fetchOne(
            "SELECT * FROM `users` WHERE (`phone` = :id1 OR `email` = :id2) LIMIT 1",
            ['id1' => $identifier, 'id2' => $identifier]
        );

        if (!$user) {
            return false;
        }

        // Check if user is blocked
        if (($user['status'] ?? 'active') === 'blocked') {
            return false;
        }

        if (password_verify($password, $user['password_hash'])) {
            self::login($user);
            return true;
        }

        return false;
    }

    /**
     * Log user into session
     */
    public static function login(array $user): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_phone'] = $user['phone'];
        $_SESSION['user_role'] = $user['role'] ?? 'user';
    }

    /**
     * Check if a user is logged in
     */
    public static function check(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !empty($_SESSION['user_id']);
    }

    /**
     * Check if logged in user is admin or super admin
     */
    public static function isAdmin(): bool {
        if (!self::check()) {
            return false;
        }
        $role = $_SESSION['user_role'] ?? 'user';
        return in_array($role, ['admin', 'super_admin'], true);
    }

    /**
     * Check if logged in user is developer / super admin
     */
    public static function isSuperAdmin(): bool {
        if (!self::check()) {
            return false;
        }
        return ($_SESSION['user_role'] ?? 'user') === 'super_admin';
    }

    /**
     * Get logged in user ID
     */
    public static function id(): ?int {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get full user data from DB
     */
    public static function user(): ?array {
        $id = self::id();
        if (!$id) return null;

        return Database::fetchOne("SELECT * FROM `users` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    /**
     * Log out current user
     */
    public static function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_phone'], $_SESSION['user_role']);
        session_destroy();
    }

    /**
     * Hash password securely
     */
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verify password against hash
     */
    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
}
