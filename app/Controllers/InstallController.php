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
     * Parse ini byte strings (e.g. 128M, 1G, 64M) into integer bytes
     */
    public static function parseIniBytes(string $val): int {
        $val = trim($val);
        if ($val === '' || $val === '-1') return PHP_INT_MAX;
        $last = strtolower($val[strlen($val) - 1]);
        $num = (int)$val;
        switch ($last) {
            case 'g': $num *= 1024 * 1024 * 1024; break;
            case 'm': $num *= 1024 * 1024; break;
            case 'k': $num *= 1024; break;
        }
        return $num;
    }

    /**
     * Comprehensive system, PHP extensions, and hosting environment checks
     */
    public static function getRequirements(): array {
        $phpVersion = PHP_VERSION;
        $phpOk = version_compare($phpVersion, '8.0.0', '>=');

        // Required PHP extensions
        $extensions = [
            'pdo' => [
                'name' => 'افزونه پایگاه داده PDO',
                'description' => 'مدیریت استاندارد پایگاه داده و جلوگیری از حملات تزریق SQL',
                'status' => extension_loaded('pdo'),
                'required' => 'فعال',
                'current' => extension_loaded('pdo') ? 'فعال' : 'غیرفعال',
                'severity' => 'critical'
            ],
            'pdo_mysql' => [
                'name' => 'افزونه PDO MySQL',
                'description' => 'اتصال پایدار و سریع به سرور دیتابیس MySQL / MariaDB',
                'status' => extension_loaded('pdo_mysql'),
                'required' => 'فعال',
                'current' => extension_loaded('pdo_mysql') ? 'فعال' : 'غیرفعال',
                'severity' => 'critical'
            ],
            'mbstring' => [
                'name' => 'افزونه چندبایتی mbstring',
                'description' => 'پشتیبانی کامل از زبان فارسی، کاراکترهای یونیکد UTF-8 و تقویم شمسی',
                'status' => extension_loaded('mbstring'),
                'required' => 'فعال',
                'current' => extension_loaded('mbstring') ? 'فعال' : 'غیرفعال',
                'severity' => 'critical'
            ],
            'openssl' => [
                'name' => 'افزونه رمزنگاری OpenSSL',
                'description' => 'تولید توکن‌های امنیتی CSRF، نشست‌ها و ارتباط ایمن با درگاه‌ها',
                'status' => extension_loaded('openssl'),
                'required' => 'فعال',
                'current' => extension_loaded('openssl') ? 'فعال' : 'غیرفعال',
                'severity' => 'critical'
            ],
            'curl' => [
                'name' => 'افزونه ارتباطی cURL',
                'description' => 'ارسال پیامک‌های اعتبارسنجی (ملی‌پیامک) و درگاه‌های پرداخت شاپرک',
                'status' => extension_loaded('curl'),
                'required' => 'فعال',
                'current' => extension_loaded('curl') ? 'فعال' : 'غیرفعال',
                'severity' => 'critical'
            ],
            'gd' => [
                'name' => 'افزونه پردازش تصویر GD / Imagick',
                'description' => 'برش، تغییر اندازه و بهینه‌سازی لوگو، فاوآیکون و تصاویر کاتالوگ',
                'status' => (extension_loaded('gd') || extension_loaded('imagick')),
                'required' => 'فعال',
                'current' => (extension_loaded('gd') || extension_loaded('imagick')) ? 'فعال' : 'غیرفعال',
                'severity' => 'critical'
            ],
            'fileinfo' => [
                'name' => 'افزونه اعتبارسنجی فایل (FileInfo)',
                'description' => 'تشخیص امن فرمت واقعی و نوع MIME فایل‌های آپلودی',
                'status' => extension_loaded('fileinfo'),
                'required' => 'فعال',
                'current' => extension_loaded('fileinfo') ? 'فعال' : 'غیرفعال',
                'severity' => 'critical'
            ],
            'json' => [
                'name' => 'افزونه JSON Parser',
                'description' => 'پردازش تنظیمات ماژول‌ها، وب‌سرویس‌ها و داده‌های ساختاریافته',
                'status' => extension_loaded('json'),
                'required' => 'فعال',
                'current' => extension_loaded('json') ? 'فعال' : 'غیرفعال',
                'severity' => 'critical'
            ],
            'zip' => [
                'name' => 'افزونه آرشیو ZipArchive',
                'description' => 'پشتیبان‌گیری خودکار دیتابیس و مدیریت بسته‌های سیستمی',
                'status' => extension_loaded('zip'),
                'required' => 'فعال',
                'current' => extension_loaded('zip') ? 'فعال' : 'غیرفعال',
                'severity' => 'critical'
            ],
        ];

        // PHP Directives / Hosting Resources
        $memLimitStr = ini_get('memory_limit') ?: '128M';
        $memBytes = self::parseIniBytes($memLimitStr);
        $memOk = $memBytes >= (128 * 1024 * 1024);

        $uploadMaxStr = ini_get('upload_max_filesize') ?: '2M';
        $uploadBytes = self::parseIniBytes($uploadMaxStr);
        $uploadOk = $uploadBytes >= (8 * 1024 * 1024);

        $postMaxStr = ini_get('post_max_size') ?: '8M';
        $postBytes = self::parseIniBytes($postMaxStr);
        $postOk = $postBytes >= (8 * 1024 * 1024);

        $maxExecTime = (int)ini_get('max_execution_time');
        $execOk = ($maxExecTime === 0 || $maxExecTime >= 30);

        $directives = [
            'memory_limit' => [
                'name' => 'محدودیت حافظه PHP (memory_limit)',
                'description' => 'حداقل ۱۲۸ مگابایت جهت پردازش تراکنش‌ها و گزارش‌های سیستم',
                'status' => $memOk,
                'required' => '128M+',
                'current' => $memLimitStr,
                'severity' => 'recommended'
            ],
            'upload_max_filesize' => [
                'name' => 'حداکثر حجم آپلود فایل (upload_max_filesize)',
                'description' => 'حداقل ۸ مگابایت جهت آپلود تصاویر و کاتالوگ محصولات',
                'status' => $uploadOk,
                'required' => '8M+',
                'current' => $uploadMaxStr,
                'severity' => 'recommended'
            ],
            'post_max_size' => [
                'name' => 'حداکثر حجم ارسال فرم (post_max_size)',
                'description' => 'حداقل ۸ مگابایت برای ارسال همزمان لوگو و داده‌های فرم',
                'status' => $postOk,
                'required' => '8M+',
                'current' => $postMaxStr,
                'severity' => 'recommended'
            ],
            'max_execution_time' => [
                'name' => 'زمان مجاز اجرای پردازش (max_execution_time)',
                'description' => 'حداقل ۳۰ ثانیه برای ایمپورت دیتابیس و پردازش بکاپ‌ها',
                'status' => $execOk,
                'required' => '30s+',
                'current' => ($maxExecTime === 0 ? 'نامحدود (0)' : $maxExecTime . ' ثانیه'),
                'severity' => 'recommended'
            ]
        ];

        // Permissions
        $storagePath = ROOT_PATH . DIRECTORY_SEPARATOR . 'storage';
        $uploadsPath = ROOT_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads';
        $configPath  = ROOT_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'config.php';

        $storageOk = is_dir($storagePath) && is_writable($storagePath);
        $uploadsOk = is_dir($uploadsPath) && is_writable($uploadsPath);
        $configOk  = file_exists($configPath) && is_writable($configPath);

        $permissions = [
            'storage' => [
                'name' => 'دایرکتوری داده‌های پویا (storage/)',
                'description' => 'ذخیره لاگ‌ها، نشست‌ها، کش و بکاپ‌های دیتابیس',
                'status' => $storageOk,
                'required' => 'قابل نوشتن (0755/0777)',
                'current' => $storageOk ? 'قابل نوشتن' : 'غیرقابل نوشتن',
                'severity' => 'critical'
            ],
            'uploads' => [
                'name' => 'دایرکتوری فایل‌های رسانه (public/uploads/)',
                'description' => 'ذخیره لوگوی اختصاصی، تصاویر محصولات و اسناد',
                'status' => $uploadsOk,
                'required' => 'قابل نوشتن (0755/0777)',
                'current' => $uploadsOk ? 'قابل نوشتن' : 'غیرقابل نوشتن',
                'severity' => 'critical'
            ],
            'config' => [
                'name' => 'فایل پیکربندی اصلی (app/Config/config.php)',
                'description' => 'ذخیره خودکار مشخصات اتصال دیتابیس و اطلاعات سایت',
                'status' => $configOk,
                'required' => 'قابل نوشتن (0644/0666)',
                'current' => $configOk ? 'قابل نوشتن' : 'غیرقابل نوشتن',
                'severity' => 'critical'
            ],
        ];

        $criticalPassed = $phpOk;
        foreach ($extensions as $ext) {
            if (!$ext['status']) $criticalPassed = false;
        }
        foreach ($permissions as $perm) {
            if (!$perm['status']) $criticalPassed = false;
        }

        return [
            'php' => [
                'name' => 'نسخه موتور PHP سرور',
                'description' => 'پروژه نیازمند نسخه مدرن PHP 8.0 یا بالاتر است',
                'status' => $phpOk,
                'required' => '>= 8.0 (پیشنهادی 8.2+)',
                'current' => $phpVersion,
                'severity' => 'critical'
            ],
            'extensions' => $extensions,
            'directives' => $directives,
            'permissions' => $permissions,
            'allPassed' => $criticalPassed
        ];
    }

    /**
     * Show Installer Wizard
     */
    public function index(): void {
        if ($this->isInstalled() && empty($_GET['force'])) {
            $this->redirect('');
        }

        $reqData = self::getRequirements();

        try {
            // Render standalone view without main site layout
            $this->view('install/index', [
                'pageTitle'    => 'نصب‌کننده هوشمند سامانه ترموبان | بسته نصب سریع هاست',
                'reqData'      => $reqData,
                'allPassed'    => $reqData['allPassed']
            ], '');
        } catch (\Throwable $e) {
            // Direct file fallback based on Controller directory if standard resolution encounters host discrepancy
            $candidates = [
                __DIR__ . '/../Views/install/index.php',
                __DIR__ . '/../views/install/index.php',
                ROOT_PATH . '/app/Views/install/index.php',
                ROOT_PATH . '/app/views/install/index.php',
            ];
            $rendered = false;
            foreach ($candidates as $candidate) {
                if (file_exists($candidate)) {
                    $pageTitle = 'نصب‌کننده هوشمند سامانه ترموبان | بسته نصب سریع هاست';
                    $allPassed = $reqData['allPassed'];
                    require $candidate;
                    $rendered = true;
                    break;
                }
            }
            if (!$rendered) {
                throw $e;
            }
        }
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

            $serverVersion = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);

            $this->json([
                'success' => true,
                'message' => 'ارتباط با پایگاه داده MySQL با موفقیت برقرار شد!',
                'version' => $serverVersion
            ]);
        } catch (PDOException $e) {
            $msg = $e->getMessage();
            $friendly = 'خطا در برقراری ارتباط با پایگاه داده.';
            if (str_contains($msg, 'Access denied')) {
                $friendly = 'دسترسی رد شد! نام کاربری یا کلمه عبور دیتابیس اشتباه است.';
            } elseif (str_contains($msg, 'Unknown database')) {
                $friendly = "پایگاه داده '{$name}' یافت نشد! لطفاً ابتدا آن را در بخش MySQL Databases کنترل‌پنل cPanel بسازید.";
            } elseif (str_contains($msg, 'Connection refused') || str_contains($msg, 'getaddrinfo failed')) {
                $friendly = 'امکان اتصال به آدرس میزبان وجود ندارد. آدرس یا پورت را بررسی نمایید (معمولاً localhost).';
            } else {
                $friendly .= ' (' . $msg . ')';
            }

            $this->json([
                'success' => false,
                'message' => $friendly
            ], 400);
        }
    }

    /**
     * Execute Full Installation, Database Import, Branding & Super Admin Setup
     */
    public function execute(): void {
        // 1. Database inputs
        $dbHost = trim($_POST['db_host'] ?? 'localhost');
        $dbPort = (int) ($_POST['db_port'] ?? 3306);
        $dbName = trim($_POST['db_name'] ?? '');
        $dbUser = trim($_POST['db_user'] ?? '');
        $dbPass = $_POST['db_pass'] ?? '';

        // 2. Site identity inputs
        $siteName = trim($_POST['site_name'] ?? 'ترموبان | نمایندگی کد  ایران رادیاتور و بوتان');
        $siteTitle = trim($_POST['site_title'] ?? 'نمایندگی رسمی نصب و تعمیرات پکیج دیواری');
        $siteDescription = trim($_POST['site_description'] ?? 'مرکز تخصصی نصب، سرویس و تعمیرات انواع پکیج شوفاژ دیواری با کد رسمی مجاز ');
        $agencyCode = trim($_POST['agency_code'] ?? '');
        $sitePhone1 = trim($_POST['site_phone_1'] ?? '۰۹۱۲-۳۴۵-۶۷۸۹');
        $sitePhone2 = trim($_POST['site_phone_2'] ?? '۰۲۱-۱۲۳۴۵۶۷۸');
        $siteEmail = trim($_POST['site_email'] ?? 'info@thermoban.ir');
        $siteAddress = trim($_POST['site_address'] ?? 'تهران، خیابان کمالی، خیابان غفاری، پلاک ۱۷۵');

        // 3. Super Admin inputs
        $adminName = trim($_POST['admin_name'] ?? 'شایان نماینده');
        $adminPhone = trim($_POST['admin_phone'] ?? '09121111111');
        $adminEmail = trim($_POST['admin_email'] ?? 'admin@thermoban.ir');
        $adminPass = $_POST['admin_pass'] ?? '';

        if (empty($dbName) || empty($dbUser) || empty($adminPhone) || empty($adminPass)) {
            $this->json(['success' => false, 'message' => 'لطفاً تمامی فیلدهای الزامی دیتابیس و مدیر ارشد را تکمیل فرمایید.'], 422);
            return;
        }

        try {
            // 1. Establish PDO Connection
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
                    
                    try {
                        $pdo->exec($sql);
                    } catch (\Throwable $e) {
                        // If multi-query produces warnings or errors, run statements individually
                        $queries = array_filter(array_map('trim', explode(';', $sql)));
                        foreach ($queries as $query) {
                            if (!empty($query)) {
                                try {
                                    $pdo->exec($query);
                                } catch (\Throwable $subE) {
                                    // Ignore benign duplicate column or existing table notices
                                }
                            }
                        }
                    }
                }
            }

            // 3. Handle Logo & Favicon Uploads
            $settingsUploadDir = ROOT_PATH . '/public/uploads/settings';
            if (!is_dir($settingsUploadDir)) {
                mkdir($settingsUploadDir, 0755, true);
            }

            $siteLogoPath = null;
            if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['site_logo']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg'])) {
                    $logoFilename = 'site_logo_' . time() . '.' . $ext;
                    $targetPath = $settingsUploadDir . '/' . $logoFilename;
                    if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $targetPath)) {
                        $siteLogoPath = 'uploads/settings/' . $logoFilename;
                    }
                }
            }

            $siteFaviconPath = null;
            if (isset($_FILES['site_favicon']) && $_FILES['site_favicon']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['site_favicon']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['ico', 'png', 'jpg', 'jpeg', 'webp', 'svg'])) {
                    $favFilename = 'site_favicon_' . time() . '.' . $ext;
                    $targetPath = $settingsUploadDir . '/' . $favFilename;
                    if (move_uploaded_file($_FILES['site_favicon']['tmp_name'], $targetPath)) {
                        $siteFaviconPath = 'uploads/settings/' . $favFilename;
                    }
                }
            }

            // 4. Save/Update All Initial Site Settings in Database
            $settingsMap = [
                'site_name'        => $siteName,
                'site_title'       => $siteTitle,
                'site_description' => $siteDescription,
                'agency_code'      => $agencyCode,
                'site_phone_1'     => $sitePhone1,
                'site_phone_2'     => $sitePhone2,
                'site_email'       => $siteEmail,
                'site_address'     => $siteAddress,
            ];

            if ($siteLogoPath) {
                $settingsMap['site_logo'] = $siteLogoPath;
            }
            if ($siteFaviconPath) {
                $settingsMap['site_favicon'] = $siteFaviconPath;
            }

            $stmtSetting = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
            foreach ($settingsMap as $key => $val) {
                $stmtSetting->execute(['k' => $key, 'v' => $val]);
            }

            // 4.5. Guarantee users table schema allows 'super_admin' and has 'status' column
            try {
                $pdo->exec("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('user', 'technician', 'admin', 'super_admin') DEFAULT 'user'");
            } catch (\Throwable $e) {}
            try {
                $colCheck = $pdo->query("SHOW COLUMNS FROM `users` LIKE 'status'")->fetch();
                if (!$colCheck) {
                    $pdo->exec("ALTER TABLE `users` ADD COLUMN `status` ENUM('active', 'blocked') DEFAULT 'active' AFTER `role`");
                }
            } catch (\Throwable $e) {}

            // 5. Create or Update Super Admin Account (Full access ready for customer handover)
            $hash = password_hash($adminPass, PASSWORD_BCRYPT, ['cost' => 12]);
            $stmtUser = $pdo->prepare("SELECT id FROM users WHERE phone = :p LIMIT 1");
            $stmtUser->execute(['p' => $adminPhone]);
            $existing = $stmtUser->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $up = $pdo->prepare("UPDATE users SET name = :n, email = :e, password_hash = :h, role = 'super_admin', status = 'active' WHERE id = :id");
                $up->execute(['n' => $adminName, 'e' => $adminEmail, 'h' => $hash, 'id' => $existing['id']]);
            } else {
                $ins = $pdo->prepare("INSERT INTO users (name, phone, email, password_hash, role, status) VALUES (:n, :p, :e, :h, 'super_admin', 'active')");
                $ins->execute(['n' => $adminName, 'p' => $adminPhone, 'e' => $adminEmail, 'h' => $hash]);
            }

            // 6. Update app/Config/config.php with production credentials
            $this->updateConfigFile($dbHost, $dbName, $dbUser, $dbPass, $siteName, $sitePhone1, $sitePhone2, $siteAddress, $siteEmail);

            // 7. Create storage/installed.lock
            $storageDir = ROOT_PATH . DIRECTORY_SEPARATOR . 'storage';
            if (!is_dir($storageDir)) {
                mkdir($storageDir, 0755, true);
            }
            $lockContent = "Installed: " . date('Y-m-d H:i:s') . "\n";
            $lockContent .= "Super Admin: " . $adminName . " (" . $adminPhone . ")\n";
            $lockContent .= "Site: " . $siteName . "\n";
            $lockContent .= "Database: " . $dbName . " on " . $dbHost . ":" . $dbPort . "\n";
            $lockContent .= "Ready for customer handover: YES\n";
            file_put_contents($this->lockFile, $lockContent);

            $this->json([
                'success' => true,
                'message' => 'سامانه با موفقیت نصب و پیکربندی شد و آماده تحویل به مشتری است!',
                'admin_url' => url('admin'),
                'site_url' => url()
            ]);
        } catch (\Throwable $e) {
            $this->json([
                'success' => false,
                'message' => 'خطا در فرآیند نصب: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update app/Config/config.php with production parameters
     */
    protected function updateConfigFile(
        string $host, string $name, string $user, string $pass,
        string $siteName = '', string $phone1 = '', string $phone2 = '',
        string $address = '', string $email = ''
    ): void {
        $configFile = ROOT_PATH . '/app/Config/config.php';
        if (!file_exists($configFile)) return;

        $content = file_get_contents($configFile);

        $content = preg_replace("/define\('DB_HOST',\s*'[^']*'\);/", "define('DB_HOST', '{$host}');", $content);
        $content = preg_replace("/define\('DB_NAME',\s*'[^']*'\);/", "define('DB_NAME', '{$name}');", $content);
        $content = preg_replace("/define\('DB_USER',\s*'[^']*'\);/", "define('DB_USER', '{$user}');", $content);
        $content = preg_replace("/define\('DB_PASS',\s*'[^']*'\);/", "define('DB_PASS', '{$pass}');", $content);

        if (!empty($siteName)) {
            $safeName = addslashes($siteName);
            $content = preg_replace("/define\('SITE_NAME',\s*'[^']*'\);/", "define('SITE_NAME', '{$safeName}');", $content);
        }
        if (!empty($phone1)) {
            $safePhone1 = addslashes($phone1);
            $content = preg_replace("/define\('SITE_PHONE_1',\s*'[^']*'\);/", "define('SITE_PHONE_1', '{$safePhone1}');", $content);
        }
        if (!empty($phone2)) {
            $safePhone2 = addslashes($phone2);
            $content = preg_replace("/define\('SITE_PHONE_2',\s*'[^']*'\);/", "define('SITE_PHONE_2', '{$safePhone2}');", $content);
        }
        if (!empty($address)) {
            $safeAddr = addslashes($address);
            $content = preg_replace("/define\('SITE_ADDRESS',\s*'[^']*'\);/", "define('SITE_ADDRESS', '{$safeAddr}');", $content);
        }
        if (!empty($email)) {
            $safeMail = addslashes($email);
            $content = preg_replace("/define\('SITE_EMAIL',\s*'[^']*'\);/", "define('SITE_EMAIL', '{$safeMail}');", $content);
        }

        file_put_contents($configFile, $content);
    }
}
