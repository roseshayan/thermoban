<?php
namespace App\Core;

class Application {
    protected Router $router;

    public function __construct() {
        $this->router = new Router();
        Security::initHeaders();
    }

    public static function run(): void {
        // Load global helpers
        $helpersPath = defined('APP_PATH') ? APP_PATH . '/Helpers/helpers.php' : (defined('ROOT_PATH') ? ROOT_PATH . '/app/Helpers/helpers.php' : __DIR__ . '/../Helpers/helpers.php');
        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }

        spl_autoload_register(function ($class) {
            $prefix = 'App\\';
            $base_dir = APP_PATH . DIRECTORY_SEPARATOR;

            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            $relative_class = substr($class, $len);
            $file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

            if (file_exists($file)) {
                require $file;
            }
        });

        $app = new self();
        $app->registerRoutes();
        $app->router->dispatch();
    }

    protected function registerRoutes(): void {
        // Home
        $this->router->get('/', [\App\Controllers\HomeController::class, 'index']);
        $this->router->post('/api/quick-order', [\App\Controllers\HomeController::class, 'submitQuickOrder']);
        
        // Blog / Articles
        $this->router->get('/blog', [\App\Controllers\BlogController::class, 'index']);
        $this->router->get('/articles', [\App\Controllers\BlogController::class, 'index']);
        $this->router->get('/blog/{slug}', [\App\Controllers\BlogController::class, 'detail']);
        $this->router->post('/api/comment', [\App\Controllers\BlogController::class, 'submitComment']);
        
        // Shop / Products / Cart / Checkout
        $this->router->get('/shop', [\App\Controllers\ShopController::class, 'index']);
        $this->router->get('/products', [\App\Controllers\ShopController::class, 'index']);
        $this->router->get('/product/{id}', [\App\Controllers\ShopController::class, 'detail']);
        $this->router->get('/shop/{id}', [\App\Controllers\ShopController::class, 'detail']);
        $this->router->get('/cart', [\App\Controllers\ShopController::class, 'cart']);
        $this->router->post('/api/cart/add', [\App\Controllers\ShopController::class, 'addToCart']);
        $this->router->post('/api/cart/remove', [\App\Controllers\ShopController::class, 'removeFromCart']);
        $this->router->post('/api/cart/clear', [\App\Controllers\ShopController::class, 'clearCart']);
        $this->router->get('/checkout', [\App\Controllers\ShopController::class, 'checkout']);
        $this->router->post('/checkout', [\App\Controllers\ShopController::class, 'processCheckout']);
        $this->router->get('/payment/success', [\App\Controllers\ShopController::class, 'paymentSuccess']);
        $this->router->get('/payment/failed', [\App\Controllers\ShopController::class, 'paymentFailed']);
        
        // User Profile & Invoices
        $this->router->get('/profile', [\App\Controllers\UserController::class, 'profile']);
        $this->router->get('/invoice/{id}', [\App\Controllers\UserController::class, 'invoice']);
        $this->router->post('/api/user/update-info', [\App\Controllers\UserController::class, 'updateInfo']);
        $this->router->post('/api/user/add-address', [\App\Controllers\UserController::class, 'addAddress']);
        $this->router->post('/api/user/create-ticket', [\App\Controllers\UserController::class, 'createTicket']);

        // Web Installer for cPanel & Shared Hosting
        $this->router->get('/install', [\App\Controllers\InstallController::class, 'index']);
        $this->router->post('/install/test-db', [\App\Controllers\InstallController::class, 'testConnection']);
        $this->router->post('/install/execute', [\App\Controllers\InstallController::class, 'execute']);
        
        // Pages & Services
        $this->router->get('/about', [\App\Controllers\PageController::class, 'about']);
        $this->router->get('/contact', [\App\Controllers\PageController::class, 'contact']);
        $this->router->get('/services', [\App\Controllers\PageController::class, 'services']);
        $this->router->post('/api/contact', [\App\Controllers\PageController::class, 'submitContact']);

        // Comprehensive Service Booking & Tracking
        $this->router->get('/service-order', [\App\Controllers\ServiceOrderController::class, 'index']);
        $this->router->get('/order-service', [\App\Controllers\ServiceOrderController::class, 'index']);
        $this->router->post('/service-order', [\App\Controllers\ServiceOrderController::class, 'submit']);
        $this->router->get('/service-order/success', [\App\Controllers\ServiceOrderController::class, 'success']);
        $this->router->get('/service-order/tracking', [\App\Controllers\ServiceOrderController::class, 'tracking']);
        $this->router->get('/tracking', [\App\Controllers\ServiceOrderController::class, 'tracking']);

        // Authentication & Password Recovery
        $this->router->get('/login', [\App\Controllers\AuthController::class, 'showLogin']);
        $this->router->post('/login', [\App\Controllers\AuthController::class, 'processLogin']);
        $this->router->get('/register', [\App\Controllers\AuthController::class, 'showRegister']);
        $this->router->post('/register', [\App\Controllers\AuthController::class, 'processRegister']);
        $this->router->get('/forgot-password', [\App\Controllers\AuthController::class, 'showForgotPassword']);
        $this->router->post('/forgot-password', [\App\Controllers\AuthController::class, 'submitForgotPassword']);
        $this->router->get('/reset-password', [\App\Controllers\AuthController::class, 'showResetPassword']);
        $this->router->post('/reset-password', [\App\Controllers\AuthController::class, 'submitResetPassword']);
        $this->router->get('/logout', [\App\Controllers\AuthController::class, 'logout']);

        // Dedicated CMS Admin Panel
        $this->router->get('/admin', [\App\Controllers\AdminController::class, 'dashboard']);
        $this->router->get('/admin/products', [\App\Controllers\AdminController::class, 'products']);
        $this->router->post('/admin/products', [\App\Controllers\AdminController::class, 'products']);
        $this->router->get('/admin/products/edit/{id}', [\App\Controllers\AdminController::class, 'editProduct']);
        $this->router->post('/admin/products/edit/{id}', [\App\Controllers\AdminController::class, 'editProduct']);
        $this->router->get('/admin/orders', [\App\Controllers\AdminController::class, 'orders']);
        $this->router->post('/admin/orders', [\App\Controllers\AdminController::class, 'orders']);
        $this->router->get('/admin/tickets', [\App\Controllers\AdminController::class, 'tickets']);
        $this->router->post('/admin/tickets', [\App\Controllers\AdminController::class, 'tickets']);
        $this->router->get('/admin/leads', [\App\Controllers\AdminController::class, 'leads']);
        $this->router->post('/admin/leads', [\App\Controllers\AdminController::class, 'leads']);
        $this->router->get('/admin/articles', [\App\Controllers\AdminController::class, 'articles']);
        $this->router->post('/admin/articles', [\App\Controllers\AdminController::class, 'articles']);
        $this->router->get('/admin/articles/edit/{id}', [\App\Controllers\AdminController::class, 'editArticle']);
        $this->router->post('/admin/articles/edit/{id}', [\App\Controllers\AdminController::class, 'editArticle']);
        $this->router->get('/admin/users', [\App\Controllers\AdminController::class, 'users']);
        $this->router->post('/admin/users', [\App\Controllers\AdminController::class, 'users']);
        $this->router->get('/admin/addresses', [\App\Controllers\AdminController::class, 'addresses']);
        $this->router->post('/admin/addresses', [\App\Controllers\AdminController::class, 'addresses']);
        $this->router->get('/admin/tariffs', [\App\Controllers\AdminController::class, 'tariffs']);
        $this->router->post('/admin/tariffs', [\App\Controllers\AdminController::class, 'tariffs']);
        $this->router->get('/admin/service-requests', [\App\Controllers\AdminController::class, 'serviceRequests']);
        $this->router->post('/admin/service-requests', [\App\Controllers\AdminController::class, 'serviceRequests']);
        $this->router->get('/admin/schedule', [\App\Controllers\AdminController::class, 'schedule']);
        $this->router->post('/admin/schedule', [\App\Controllers\AdminController::class, 'schedule']);
        $this->router->get('/admin/menus', [\App\Controllers\AdminController::class, 'menus']);
        $this->router->post('/admin/menus', [\App\Controllers\AdminController::class, 'menus']);
        $this->router->get('/admin/faqs', [\App\Controllers\AdminController::class, 'faqs']);
        $this->router->post('/admin/faqs', [\App\Controllers\AdminController::class, 'faqs']);
        $this->router->get('/admin/testimonials', [\App\Controllers\AdminController::class, 'testimonials']);
        $this->router->post('/admin/testimonials', [\App\Controllers\AdminController::class, 'testimonials']);
        $this->router->get('/admin/branches', [\App\Controllers\AdminController::class, 'branches']);
        $this->router->post('/admin/branches', [\App\Controllers\AdminController::class, 'branches']);
        $this->router->get('/admin/seo', [\App\Controllers\AdminController::class, 'seo']);
        $this->router->post('/admin/seo', [\App\Controllers\AdminController::class, 'seo']);
        $this->router->get('/admin/gateways', [\App\Controllers\AdminController::class, 'gateways']);
        $this->router->post('/admin/gateways', [\App\Controllers\AdminController::class, 'gateways']);
        $this->router->get('/admin/sms', [\App\Controllers\AdminController::class, 'sms']);
        $this->router->post('/admin/sms', [\App\Controllers\AdminController::class, 'sms']);
        $this->router->get('/admin/modules', [\App\Controllers\AdminController::class, 'modules']);
        $this->router->post('/admin/modules', [\App\Controllers\AdminController::class, 'modules']);
        $this->router->get('/admin/settings', [\App\Controllers\AdminController::class, 'settings']);
        $this->router->post('/admin/settings', [\App\Controllers\AdminController::class, 'settings']);
        $this->router->get('/admin/migrate-db', [\App\Controllers\AdminController::class, 'migrateDb']);
        $this->router->post('/admin/migrate-db', [\App\Controllers\AdminController::class, 'migrateDb']);
        
        $this->router->get('/admin/brands', [\App\Controllers\AdminController::class, 'brands']);
        $this->router->post('/admin/brands', [\App\Controllers\AdminController::class, 'brands']);
        $this->router->get('/admin/media', [\App\Controllers\AdminController::class, 'media']);
        $this->router->post('/admin/media/upload', [\App\Controllers\AdminController::class, 'uploadMedia']);
        $this->router->post('/admin/media/delete', [\App\Controllers\AdminController::class, 'deleteMedia']);
        $this->router->get('/admin/profile', [\App\Controllers\AdminController::class, 'profile']);
        $this->router->post('/admin/profile', [\App\Controllers\AdminController::class, 'profile']);
        
        // Admin Backups, Analytics & Comments
        $this->router->get('/admin/backups', [\App\Controllers\AdminController::class, 'backups']);
        $this->router->post('/admin/backups', [\App\Controllers\AdminController::class, 'backups']);
        $this->router->get('/admin/backups/download/{file}', [\App\Controllers\AdminController::class, 'downloadBackup']);
        $this->router->get('/admin/analytics', [\App\Controllers\AdminController::class, 'analytics']);
        $this->router->get('/admin/comments', [\App\Controllers\AdminController::class, 'comments']);
        $this->router->post('/admin/comments', [\App\Controllers\AdminController::class, 'comments']);
        $this->router->post('/admin/notifications/mark-read', [\App\Controllers\AdminController::class, 'markNotificationsRead']);
    }
}
