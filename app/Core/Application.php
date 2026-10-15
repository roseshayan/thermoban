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
        
        // Quick Order API / Form submission
        $this->router->post('/api/quick-order', [\App\Controllers\HomeController::class, 'submitQuickOrder']);
        
        // Additional UI pages if visited directly
        $this->router->get('/services', [\App\Controllers\HomeController::class, 'index']);
        $this->router->get('/shop', [\App\Controllers\HomeController::class, 'index']);
        $this->router->get('/blog', [\App\Controllers\HomeController::class, 'index']);
        $this->router->get('/contact', [\App\Controllers\HomeController::class, 'index']);
        $this->router->get('/about', [\App\Controllers\HomeController::class, 'index']);
    }
}
