<?php
namespace App\Core;

class Application {
    protected Router $router;

    public function __construct() {
        $this->router = new Router();
        Security::initHeaders();
    }

    public static function run(): void {
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
        
        // Shop / Products
        $this->router->get('/shop', [\App\Controllers\ShopController::class, 'index']);
        $this->router->get('/products', [\App\Controllers\ShopController::class, 'index']);
        $this->router->get('/product/{id}', [\App\Controllers\ShopController::class, 'detail']);
        $this->router->get('/shop/{id}', [\App\Controllers\ShopController::class, 'detail']);
        
        // Pages & Services
        $this->router->get('/about', [\App\Controllers\PageController::class, 'about']);
        $this->router->get('/contact', [\App\Controllers\PageController::class, 'contact']);
        $this->router->get('/services', [\App\Controllers\PageController::class, 'services']);
        $this->router->post('/api/contact', [\App\Controllers\PageController::class, 'submitContact']);
    }
}
