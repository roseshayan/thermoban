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

    private function testErrorTemplates(): void {
        $this->assert("404 Error Template Exists", file_exists(VIEW_PATH . '/errors/404.php'));
        $this->assert("500 Error Template Exists", file_exists(VIEW_PATH . '/errors/500.php'));
        $this->assert("Maintenance 503 Template Exists", file_exists(VIEW_PATH . '/errors/maintenance.php'));
    }
}

$runner = new SmokeTestRunner();
$runner->run();
