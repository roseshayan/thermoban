<?php
namespace App\Controllers;

use App\Core\Controller;
use PDO;
use PDOException;

class InstallController extends Controller {

    protected string $lockFile = ROOT_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'installed.lock';

    public function isInstalled(): bool {
        return file_exists($this->lockFile);
    }

    /**
     * Show Installer Wizard
     */
    public function index(): void {
        if ($this->isInstalled() && empty($_GET['force'])) {
            $this->redirect('');
        }

        // Check Requirements
        $requirements = [
            'php_version' => [
                'name'    => 'نسخه PHP (حداقل 8.0)',
                'current' => PHP_VERSION,
                'status'  => version_compare(PHP_VERSION, '8.0.0', '>=')
            ],
            'pdo_mysql' => [
                'name'    => 'افزونه PDO MySQL',
                'current' => extension_loaded('pdo_mysql') ? 'فعال' : 'غیرفعال',
                'status'  => extension_loaded('pdo_mysql')
            ],
            'curl' => [
                'name'    => 'افزونه cURL',
                'current' => extension_loaded('curl') ? 'فعال' : 'غیرفعال',
                'status'  => extension_loaded('curl')
            ],
            'mbstring' => [
                'name'    => 'افزونه mbstring',
                'current' => extension_loaded('mbstring') ? 'فعال' : 'غیرفعال',
                'status'  => extension_loaded('mbstring')
            ],
            'storage_writable' => [
                'name'    => 'قابلیت نوشتن پوشه storage/',
                'current' => is_writable(ROOT_PATH) ? 'قابل نوشتن' : 'غیرقابل نوشتن',
                'status'  => is_writable(ROOT_PATH)
            ]
        ];

        $allPassed = true;
        foreach ($requirements as $r) {
            if (!$r['status']) {
                $allPassed = false;
                break;
            }
        }

        // Render standalone view without main site layout
        $this->view('install/index', [
            'pageTitle'    => 'نصب‌کننده خودکار سامانه ترموبان | نصب سریع cPanel',
            'requirements' => $requirements,
            'allPassed'    => $allPassed
        ], '');
    }

    /**
     * AJAX: Test Database Connection
     */
    public function testConnection(): void {
        $host = trim($_POST['db_host'] ?? 'localhost');
        $port = (int) ($_POST['db_port'] ?? 3306);
        $name = trim($_POST['db_name'] ?? '');
        $user = trim($_POST['db_user'] ?? '');
        $pass = $_POST['db_pass'] ?? '';

        if (empty($name) || empty($user)) {
            $this->json(['success' => false, 'message' => 'نام پایگاه داده و نام کاربری الزامی است.'], 422);
            return;
        }

        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5
            ]);

            $this->json([
                'success' => true,
                'message' => 'ارتباط با پایگاه داده MySQL با موفقیت برقرار شد!'
            ]);
        } catch (PDOException $e) {
            $this->json([
                'success' => false,
                'message' => 'خطا در اتصال به دیتابیس: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Execute Full Installation
     */
    public function execute(): void {
        $dbHost = trim($_POST['db_host'] ?? 'localhost');
        $dbPort = (int) ($_POST['db_port'] ?? 3306);
        $dbName = trim($_POST['db_name'] ?? '');
        $dbUser = trim($_POST['db_user'] ?? '');
        $dbPass = $_POST['db_pass'] ?? '';

        $siteName = trim($_POST['site_name'] ?? 'ترموبان | نمایندگی کد ۷۲۷۳ ایران رادیاتور و بوتان');
        $adminName = trim($_POST['admin_name'] ?? 'شایان نماینده');
        $adminPhone = trim($_POST['admin_phone'] ?? '09121111111');
        $adminPass = $_POST['admin_pass'] ?? 'admin123';

        if (empty($dbName) || empty($dbUser) || empty($adminPhone) || empty($adminPass)) {
            $this->json(['success' => false, 'message' => 'لطفاً تمامی فیلدهای الزامی را تکمیل فرمایید.'], 422);
            return;
        }

        try {
            // 1. Test PDO connection
            $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            // 2. Execute SQL Schemas safely
            $schemaFiles = [
                ROOT_PATH . '/database/schema.sql',
                ROOT_PATH . '/database/upgrade_schema.sql',
                ROOT_PATH . '/database/v2_update.sql',
                ROOT_PATH . '/database/v3_update.sql'
            ];

            foreach ($schemaFiles as $file) {
                if (file_exists($file)) {
                    $sql = file_get_contents($file);
                    // Filter out any stray CREATE DATABASE or USE statements for cPanel portability
                    $sql = preg_replace('/CREATE\s+DATABASE\s+[^;]+;/i', '', $sql);
                    $sql = preg_replace('/USE\s+[^;]+;/i', '', $sql);
                    $pdo->exec($sql);
                }
            }

            // 3. Insert or update Super Admin
            $hash = password_hash($adminPass, PASSWORD_BCRYPT, ['cost' => 12]);
            $stmt = $pdo->prepare("SELECT id FROM users WHERE phone = :p LIMIT 1");
            $stmt->execute(['p' => $adminPhone]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $up = $pdo->prepare("UPDATE users SET name = :n, password_hash = :h, role = 'super_admin', status = 'active' WHERE id = :id");
                $up->execute(['n' => $adminName, 'h' => $hash, 'id' => $existing['id']]);
            } else {
                $ins = $pdo->prepare("INSERT INTO users (name, phone, password_hash, role, status) VALUES (:n, :p, :h, 'super_admin', 'active')");
                $ins->execute(['n' => $adminName, 'p' => $adminPhone, 'h' => $hash]);
            }

            // 4. Update site name in settings
            $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('site_name', :s) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
            $stmt->execute(['s' => $siteName]);

            // 5. Update app/Config/config.php
            $this->updateConfigFile($dbHost, $dbName, $dbUser, $dbPass);

            // 6. Create storage/installed.lock
            $storageDir = ROOT_PATH . DIRECTORY_SEPARATOR . 'storage';
            if (!is_dir($storageDir)) {
                mkdir($storageDir, 0755, true);
            }
            file_put_contents($this->lockFile, "Installed on " . date('Y-m-d H:i:s') . "\nBy " . $adminName . " (" . $adminPhone . ")\n");

            $this->json([
                'success' => true,
                'message' => 'سامانه ترموبان با موفقیت کامل روی هاست شما نصب گردید!'
            ]);
        } catch (\Throwable $e) {
            $this->json([
                'success' => false,
                'message' => 'خطا در فرآیند نصب: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update app/Config/config.php with production credentials
     */
    protected function updateConfigFile(string $host, string $name, string $user, string $pass): void {
        $configFile = ROOT_PATH . '/app/Config/config.php';
        if (!file_exists($configFile)) return;

        $content = file_get_contents($configFile);

        $content = preg_replace("/define\('DB_HOST',\s*'[^']*'\);/", "define('DB_HOST', '{$host}');", $content);
        $content = preg_replace("/define\('DB_NAME',\s*'[^']*'\);/", "define('DB_NAME', '{$name}');", $content);
        $content = preg_replace("/define\('DB_USER',\s*'[^']*'\);/", "define('DB_USER', '{$user}');", $content);
        $content = preg_replace("/define\('DB_PASS',\s*'[^']*'\);/", "define('DB_PASS', '{$pass}');", $content);

        file_put_contents($configFile, $content);
    }
}
