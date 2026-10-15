<?php
namespace App\Core;

abstract class Model {
    protected static string $table = '';
    protected static string $primaryKey = 'id';

    /**
     * Find by ID
     */
    public static function find(int|string $id): ?array {
        $table = static::$table;
        $pk = static::$primaryKey;
        return Database::fetchOne("SELECT * FROM `{$table}` WHERE `{$pk}` = :id LIMIT 1", ['id' => $id]);
    }

    /**
     * Get all records
     */
    public static function all(string $orderBy = 'id DESC'): array {
        $table = static::$table;
        return Database::fetchAll("SELECT * FROM `{$table}` ORDER BY {$orderBy}");
    }

    /**
     * Where query
     */
    public static function where(string $condition, array $params = [], string $orderBy = 'id DESC', ?int $limit = null): array {
        $table = static::$table;
        $sql = "SELECT * FROM `{$table}` WHERE {$condition} ORDER BY {$orderBy}";
        if ($limit !== null) {
            $sql .= " LIMIT {$limit}";
        }
        return Database::fetchAll($sql, $params);
    }

    /**
     * Find first record matching condition
     */
    public static function first(string $condition, array $params = []): ?array {
        $table = static::$table;
        return Database::fetchOne("SELECT * FROM `{$table}` WHERE {$condition} LIMIT 1", $params);
    }

    /**
     * Create new record
     */
    public static function create(array $data): int {
        return Database::insert(static::$table, $data);
    }

    /**
     * Update by ID
     */
    public static function update(int|string $id, array $data): int {
        $table = static::$table;
        $pk = static::$primaryKey;
        return Database::update($table, $data, "`{$pk}` = :id", ['id' => $id]);
    }

    /**
     * Delete by ID
     */
    public static function delete(int|string $id): int {
        $table = static::$table;
        $pk = static::$primaryKey;
        return Database::delete($table, "`{$pk}` = :id", ['id' => $id]);
    }

    /**
     * Count records
     */
    public static function count(string $condition = '1=1', array $params = []): int {
        $table = static::$table;
        $res = Database::fetchOne("SELECT COUNT(*) as total FROM `{$table}` WHERE {$condition}", $params);
        return (int) ($res['total'] ?? 0);
    }
}
