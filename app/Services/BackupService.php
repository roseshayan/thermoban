<?php
namespace App\Services;

use App\Core\Database;
use App\Models\Setting;
use App\Services\Storage\FilesIrService;
use PDO;

class BackupService {
    protected static string $backupDir = ROOT_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'backups';

    /**
     * Ensure backup directory exists with safety .htaccess
     */
    protected static function ensureDirectory(): void {
        if (!is_dir(self::$backupDir)) {
            mkdir(self::$backupDir, 0755, true);
        }

        $htaccess = self::$backupDir . DIRECTORY_SEPARATOR . '.htaccess';
        if (!file_exists($htaccess)) {
            file_put_contents($htaccess, "Deny from all\n");
        }
    }

    /**
     * Create a new database backup and enforce 2-backup retention
     */
    public static function createBackup(bool $uploadToCloud = false): array {
        self::ensureDirectory();

        $pdo = Database::getInstance();
        $dbName = DB_NAME;

        // Fetch all tables
        $tables = [];
        $stmt = $pdo->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }

        $sql = "-- ========================================================\n";
        $sql .= "-- Thermoban Automated Backup Dump\n";
        $sql .= "-- Date: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Database: {$dbName}\n";
        $sql .= "-- Dealership Code: 7273\n";
        $sql .= "-- ========================================================\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

        foreach ($tables as $table) {
            // Get CREATE TABLE
            $createStmt = $pdo->query("SHOW CREATE TABLE `{$table}`");
            $createRow = $createStmt->fetch(PDO::FETCH_ASSOC);
            $createTableSql = $createRow['Create Table'] ?? '';

            $sql .= "-- --------------------------------------------------------\n";
            $sql .= "-- Table structure for `{$table}`\n";
            $sql .= "-- --------------------------------------------------------\n";
            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $sql .= $createTableSql . ";\n\n";

            // Dump Data
            $dataStmt = $pdo->query("SELECT * FROM `{$table}`");
            $rows = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($rows)) {
                $sql .= "-- Dumping data for `{$table}`\n";
                foreach ($rows as $row) {
                    $cols = array_map(fn($col) => "`" . str_replace("`", "``", $col) . "`", array_keys($row));
                    $values = array_map(function ($val) use ($pdo) {
                        if ($val === null) return 'NULL';
                        return $pdo->quote($val);
                    }, array_values($row));

                    $sql .= "INSERT INTO `{$table}` (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $values) . ");\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

        // Generate filename
        $filename = 'thermoban_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $fullPath = self::$backupDir . DIRECTORY_SEPARATOR . $filename;

        file_put_contents($fullPath, $sql);

        // Enforce: Always keep ONLY the last 2 backups!
        self::enforceRetention();

        // Update last run time in settings
        Setting::set('backup_last_run', date('Y-m-d H:i:s'));

        // Handle cloud upload if requested or enabled
        $cloudResult = null;
        if ($uploadToCloud || Setting::get('module_files_ir', '0') === '1') {
            $cloud = new FilesIrService();
            $cloudResult = $cloud->uploadFile($fullPath);
        }

        return [
            'success'      => true,
            'filename'     => $filename,
            'size'         => self::formatSize(filesize($fullPath)),
            'cloud_result' => $cloudResult,
            'message'      => 'پشتیبان‌گیری جدید با موفقیت ایجاد شد.' . ($cloudResult && $cloudResult['success'] ? ' و به هاست ابری files.ir ارسال گردید.' : '')
        ];
    }

    /**
     * Restore database from an existing backup file
     */
    public static function restoreBackup(string $filename): array {
        self::ensureDirectory();

        // Sanitize filename to prevent path traversal
        $filename = basename($filename);
        $fullPath = self::$backupDir . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($fullPath)) {
            return [
                'success' => false,
                'message' => 'فایل پشتیبان انتخابی یافت نشد.'
            ];
        }

        $sql = file_get_contents($fullPath);
        if (empty($sql)) {
            return [
                'success' => false,
                'message' => 'محتوای فایل پشتیبان خالی است.'
            ];
        }

        $pdo = Database::getInstance();

        try {
            // Execute the restore SQL
            $pdo->exec($sql);
            return [
                'success' => true,
                'message' => 'پایگاه داده با موفقیت از فایل ' . $filename . ' بازیابی شد.'
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'خطا در بازیابی پایگاه داده: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get list of backups on disk (maximum 2 files kept)
     */
    public static function getBackups(): array {
        self::ensureDirectory();

        $files = glob(self::$backupDir . DIRECTORY_SEPARATOR . '*.sql');
        $list = [];

        foreach ($files as $f) {
            $list[] = [
                'filename'   => basename($f),
                'size'       => self::formatSize(filesize($f)),
                'size_bytes' => filesize($f),
                'created_at' => date('Y-m-d H:i:s', filemtime($f)),
                'timestamp'  => filemtime($f)
            ];
        }

        // Sort newest first
        usort($list, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);

        return $list;
    }

    /**
     * Delete a backup file
     */
    public static function deleteBackup(string $filename): bool {
        self::ensureDirectory();
        $filename = basename($filename);
        $filePath = self::$backupDir . DIRECTORY_SEPARATOR . $filename;
        if (file_exists($filePath)) {
            return @unlink($filePath);
        }
        return false;
    }

    /**
     * Get absolute path of a backup file
     */
    public static function getBackupPath(string $filename): ?string {
        self::ensureDirectory();
        $filename = basename($filename);
        $filePath = self::$backupDir . DIRECTORY_SEPARATOR . $filename;
        return file_exists($filePath) ? $filePath : null;
    }

    /**
     * Always retain only the latest 2 backups and remove older files
     */
    protected static function enforceRetention(): void {
        $backups = self::getBackups();

        if (count($backups) > 2) {
            $toDelete = array_slice($backups, 2);
            foreach ($toDelete as $item) {
                $filePath = self::$backupDir . DIRECTORY_SEPARATOR . $item['filename'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }
    }

    /**
     * Format bytes to readable string
     */
    protected static function formatSize(int $bytes): string {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' مگابایت';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' کیلوبایت';
        }
        return $bytes . ' بایت';
    }
}
