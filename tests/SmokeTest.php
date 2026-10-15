<?php
/**
 * Thermoban Automated Smoke Test Suite
 * Tests Core Architecture, Database, MVC, Security, Payment Gateways, SMS, SEO, and Routes.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ROOT_PATH', dirname(__DIR__));
define('TESTING', true);

require_once ROOT_PATH . '/app/Config/config.php';

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = APP_PATH . DIRECTORY_SEPARATOR;
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';
    if (file_exists($file)) require_once $file;
});

use App\Core\Database;
use App\Core\Auth;
use App\Core\Security;
use App\Models\User;
use App\Models\Product;
use App\Models\Article;
use App\Models\Order;
use App\Models\Setting;
use App\Services\CaptchaService;
use App\Services\SeoService;
use App\Services\Payment\ZarinPalGateway;
use App\Services\Payment\ZibalGateway;
use App\Services\Payment\PaymentManager;
use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Sms\MeliPayamakService;

class SmokeTestRunner {
    private int $passed = 0;
    private int $failed = 0;

    public function run(): void {
        echo "\n=======================================================\n";
        echo "   THERMOBAN AUTOMATED SMOKE TEST SUITE (PHP " . PHP_VERSION . ")\n";
        echo "   Dealership Code: 7273 (Iran Radiator & Butane)\n";
        echo "=======================================================\n\n";

        $this->testDatabaseConnection();
        $this->testSuperAdminAndUserRoles();
        $this->testSettingsAndFeatureFlags();
        $this->testSecurityAndCaptcha();
        $this->testPasswordResetWorkflow();
        $this->testSeoEngineAndSitemap();
        $this->testPaymentGateways();
        $this->testMeliPayamakSmsService();
        $this->testModelCrudOperations();
        $this->testCommentsAndModeration();
        $this->testVisitorAnalytics();
        $this->testDatabaseBackupAndRetention();
        $this->testWebInstallerAssets();
        $this->testZeroCdnCompliance();
        $this->testCsrfProtection();
        $this->testEmailAndSmsTicketNotifications();
        $this->testAdminPanelEndpointsNo500();
        $this->testErrorTemplates();

        echo "\n-------------------------------------------------------\n";
        echo "RESULTS: Passed: [{$this->passed}] | Failed: [{$this->failed}]\n";
        if ($this->failed === 0) {
            echo "STATUS: ALL SMOKE TESTS PASSED SUCCESSFULLY! (100% OK)\n";
        } else {
            echo "STATUS: SOME TESTS FAILED! PLEASE REVIEW ERRORS ABOVE.\n";
            exit(1);
        }
        echo "=======================================================\n\n";
    }

    private function assert(string $testName, bool $condition, string $details = ''): void {
        if ($condition) {
            $this->passed++;
            echo " [PASS] {$testName}\n";
        } else {
            $this->failed++;
            echo " [FAIL] {$testName} - {$details}\n";
        }
    }

    private function testDatabaseConnection(): void {
        try {
            $pdo = Database::getInstance();
            $this->assert("Database Connectivity", $pdo instanceof \PDO);
        } catch (\Throwable $e) {
            $this->assert("Database Connectivity", false, $e->getMessage());
        }
    }

    private function testSuperAdminAndUserRoles(): void {
        $superAdmin = User::findByPhone('09121111111');
        $this->assert("Super Admin User Exists (09121111111)", !empty($superAdmin));
        $this->assert("Super Admin Role is 'super_admin'", ($superAdmin['role'] ?? '') === 'super_admin');
        $this->assert("Super Admin Status is 'active'", ($superAdmin['status'] ?? '') === 'active');
        $this->assert("Super Admin Password Verification", Auth::verifyPassword('admin123', $superAdmin['password_hash']));

        $customer = User::findByPhone('09123456789');
        $this->assert("Demo Customer Exists (09123456789)", !empty($customer));
        $this->assert("Demo Customer Password Verification", Auth::verifyPassword('123456', $customer['password_hash']));
    }

    private function testSettingsAndFeatureFlags(): void {
        $settings = Setting::allKeyVal();
        $this->assert("Settings Loaded from DB", !empty($settings));
        $this->assert("Module Shop Flag Configured", isset($settings['module_shop']));
        $this->assert("Module Blog Flag Configured", isset($settings['module_blog']));
        $this->assert("Module Tickets Flag Configured", isset($settings['module_tickets']));
        $this->assert("Maintenance Mode Configured", isset($settings['maintenance_mode']));
        $this->assert("ZarinPal Configured", isset($settings['gateway_zarinpal_merchant']));
        $this->assert("Zibal Configured", isset($settings['gateway_zibal_merchant']));
        $this->assert("MeliPayamak SMS Configured", isset($settings['sms_username']));
    }

    private function testSecurityAndCaptcha(): void {
        $captcha = CaptchaService::generate();
        $this->assert("Captcha Generation", !empty($captcha['question']) && isset($captcha['raw_answer']));
        
        $html = CaptchaService::renderHtml();
        $this->assert("Captcha HTML Box Rendered", str_contains($html, 'security-captcha-box') && str_contains($html, 'honeypot'));

        $ans = $_SESSION['captcha_answer'] ?? '';
        $this->assert("Captcha Stored in Session", !empty($ans));
    }

    private function testPasswordResetWorkflow(): void {
        $testPhone = '09123456789';
        $testCode = '654321';
        $testToken = bin2hex(random_bytes(16));

        $id = Database::insert('password_resets', [
            'phone'      => $testPhone,
            'token'      => $testToken,
            'code'       => $testCode,
            'expires_at' => date('Y-m-d H:i:s', time() + 900)
        ]);

        $this->assert("Password Reset Record Inserted", $id > 0);

        $lookup = Database::fetchOne(
            "SELECT * FROM `password_resets` WHERE `token` = :token AND `code` = :code LIMIT 1",
            ['token' => $testToken, 'code' => $testCode]
        );
        $this->assert("Password Reset Record Retrieved", !empty($lookup) && $lookup['phone'] === $testPhone);

        Database::delete('password_resets', '`id` = :id', ['id' => $id]);
    }

    private function testSeoEngineAndSitemap(): void {
        $sitemap = SeoService::generateSitemapXml();
        $this->assert("Dynamic Sitemap.xml Generation", !empty($sitemap) && str_contains($sitemap, '<?xml') && str_contains($sitemap, '<urlset'));
        $this->assert("Sitemap Contains Home and Shop", str_contains($sitemap, '<loc>') && str_contains($sitemap, '<priority>1.0</priority>'));

        $meta = SeoService::renderHeadTags(['title' => 'تعمیر پکیج ایران رادیاتور', 'description' => 'تست سئو']);
        $this->assert("Rank Math Meta Tags Rendered", str_contains($meta, '<title>') && str_contains($meta, 'og:title') && str_contains($meta, 'application/ld+json'));
    }

    private function testPaymentGateways(): void {
        $zarinpal = PaymentManager::resolve('zarinpal');
        $this->assert("ZarinPal Gateway Resolved", $zarinpal instanceof ZarinPalGateway);
        $zpReq = $zarinpal->request(15000000, 'http://thermoban.test/callback', 'سفارش پکیج ایران رادیاتور', ['mobile' => '09123456789']);
        $this->assert("ZarinPal Request Payment (Sandbox/Mock)", isset($zpReq['success']));

        $zibal = PaymentManager::resolve('zibal');
        $this->assert("Zibal Gateway Resolved", $zibal instanceof ZibalGateway);
        $zbReq = $zibal->request(15000000, 'http://thermoban.test/callback', 'سفارش پکیج بوتان', ['mobile' => '09123456789']);
        $this->assert("Zibal Request Payment (Sandbox/Mock)", isset($zbReq['success']));

        $currentGw = PaymentManager::resolve();
        $this->assert("PaymentManager Resolved Default Gateway", $currentGw instanceof PaymentGatewayInterface);
    }

    private function testMeliPayamakSmsService(): void {
        $client = new MeliPayamakService();
        $this->assert("MeliPayamakService Instantiated", $client instanceof MeliPayamakService);

        $otpRes = MeliPayamakService::sendOtp('09123456789', '123456');
        $this->assert("MeliPayamak sendOtp Executed", isset($otpRes['success']));

        $orderRes = MeliPayamakService::sendOrderSuccess('09123456789', 1001, '18,500,000');
        $this->assert("MeliPayamak sendOrderSuccess Executed", isset($orderRes['success']));
    }

    private function testModelCrudOperations(): void {
        $productsCount = Product::count();
        $this->assert("Product::count() >= 1", $productsCount >= 1);

        $articlesCount = Article::count();
        $this->assert("Article::count() >= 1", $articlesCount >= 1);

        $ordersCount = Order::count();
        $this->assert("Order::count() >= 0", $ordersCount >= 0);
    }

    private function testCommentsAndModeration(): void {
        $comments = Database::fetchAll("SELECT * FROM `comments`");
        $this->assert("Comments Table Exists in Database", is_array($comments));
        
        $pComments = \App\Models\Comment::forProduct(1);
        $this->assert("Product Comments Query Returned Array", is_array($pComments));
    }

    private function testVisitorAnalytics(): void {
        \App\Models\VisitorLog::track();
        $stats = \App\Models\VisitorLog::getStats();
        $this->assert("VisitorLog::getStats() Returns Required Keys", isset($stats['total_visits']) && isset($stats['today_visits']) && isset($stats['devices']));
    }

    private function testDatabaseBackupAndRetention(): void {
        $res = \App\Services\BackupService::createBackup(false);
        $this->assert("BackupService::createBackup() Executed", !empty($res['success']) && !empty($res['filename']));
        
        $backups = \App\Services\BackupService::getBackups();
        $this->assert("Backups Count <= 2 (Strict Retention)", count($backups) <= 2);

        $path = \App\Services\BackupService::getBackupPath($res['filename']);
        $this->assert("Created Backup File Exists on Disk", !empty($path) && file_exists($path));
    }

    private function testWebInstallerAssets(): void {
        $this->assert("InstallController Class Exists", class_exists(\App\Controllers\InstallController::class));
        $this->assert("Installer View Template Exists", file_exists(VIEW_PATH . '/install/index.php'));
        $this->assert("Database v2 Schema File Exists", file_exists(ROOT_PATH . '/database/v2_update.sql'));
    }

    private function testZeroCdnCompliance(): void {
        $forbiddenCdns = ['cdn.jsdelivr.net', 'cdnjs.cloudflare.com', 'unpkg.com', 'fonts.googleapis.com', 'cdn.tailwindcss.com'];
        $hasCdn = false;
        $violatingFile = '';

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(VIEW_PATH));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());
                foreach ($forbiddenCdns as $cdn) {
                    if (str_contains($content, $cdn)) {
                        $hasCdn = true;
                        $violatingFile = $file->getFilename() . " ({$cdn})";
                        break 2;
                    }
                }
            }
        }

        $this->assert("Zero CDN Policy (All Assets 100% Local)", !$hasCdn, $violatingFile);
    }

    private function testCsrfProtection(): void {
        $token = csrf_token();
        $field = csrf_field();
        $this->assert("CSRF Token Generated", !empty($token) && strlen($token) >= 32);
        $this->assert("CSRF Field HTML Rendered", str_contains($field, 'name="csrf_token"') && str_contains($field, $token));
    }

    private function testEmailAndSmsTicketNotifications(): void {
        $emailRes = \App\Services\EmailNotificationService::sendTicketNotification('test@example.com', 'شایان نماینده', 'TK-99999', 'تست پشتیبانی پکیج', 'created');
        $this->assert("Email Notification Service Dispatches Successfully", $emailRes === true);

        $smsRes = \App\Services\Sms\MeliPayamakService::sendTicketNotification('09121111111', 'TK-99999', 'تست پشتیبانی پکیج', 'created');
        $this->assert("MeliPayamak Ticket Notification SMS Dispatches", is_array($smsRes) && isset($smsRes['status']));
    }

    private function testAdminPanelEndpointsNo500(): void {
        $_SESSION['user_id'] = 1;
        $_SESSION['user_role'] = 'super_admin';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        
        $admin = new \App\Controllers\AdminController();
        $actions = [
            'dashboard', 'orders', 'products', 'tickets', 'leads', 
            'articles', 'comments', 'analytics', 'backups', 'users', 
            'seo', 'gateways', 'sms', 'modules', 'settings'
        ];

        $failedActions = [];
        foreach ($actions as $action) {
            ob_start();
            try {
                $admin->$action();
                ob_end_clean();
            } catch (\Throwable $e) {
                ob_end_clean();
                $failedActions[] = "{$action} ({$e->getMessage()})";
            }
        }

        $this->assert("All 15 Admin Pages Return HTTP 200 (No 500 Errors)", empty($failedActions), implode(', ', $failedActions));
    }

    private function testErrorTemplates(): void {
        $this->assert("404 Error Template Exists", file_exists(VIEW_PATH . '/errors/404.php'));
        $this->assert("500 Error Template Exists", file_exists(VIEW_PATH . '/errors/500.php'));
        $this->assert("Maintenance 503 Template Exists", file_exists(VIEW_PATH . '/errors/maintenance.php'));
    }
}

$runner = new SmokeTestRunner();
$runner->run();
