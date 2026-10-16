<?php
namespace App\Core;

class Router {
    protected array $routes = [];

    public function get(string $path, $handler): void {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler): void {
        $this->addRoute('POST', $path, $handler);
    }

    protected function addRoute(string $method, string $path, $handler): void {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'path'    => '/' . trim($path, '/'),
            'handler' => $handler
        ];
    }

    public function dispatch(): void {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Strip query string
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Clean subfolder path from URI if running under /thermoban or /thermoban/public
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($scriptName !== '/' && $scriptName !== '.') {
            if (strpos($uri, $scriptName) === 0) {
                $uri = substr($uri, strlen($scriptName));
            }
        }
        
        $uri = '/' . trim($uri, '/');
        if ($uri === '//') $uri = '/';

        // 0. Check Installation State
        $lockFile = ROOT_PATH . '/storage/installed.lock';
        $isInstallRoute = (strpos($uri, '/install') === 0);
        if (!file_exists($lockFile)) {
            if (!$isInstallRoute) {
                header('Location: ' . url('install'));
                exit;
            }
        }

        // If install route, skip analytics, maintenance and module guards that query DB
        if (!$isInstallRoute) {
            // Track visitor log if analytics enabled
            \App\Models\VisitorLog::track();
        }

        // 1. Dynamic Sitemap & Robots.txt handling
        if ($uri === '/sitemap.xml') {
            header('Content-Type: application/xml; charset=utf-8');
            echo \App\Services\SeoService::generateSitemapXml();
            return;
        }

        if ($uri === '/robots.txt') {
            header('Content-Type: text/plain; charset=utf-8');
            echo \App\Models\Setting::get('seo_robots_txt', "User-agent: *\nDisallow: /admin/\nAllow: /");
            return;
        }

        if (!$isInstallRoute) {
            // 2. Check Maintenance Mode
            $isMaintenance = \App\Models\Setting::get('maintenance_mode', '0') === '1';
            if ($isMaintenance && !Auth::isAdmin()) {
                $isAuthRoute = (strpos($uri, '/login') === 0 || strpos($uri, '/logout') === 0 || strpos($uri, '/admin') === 0);
                if (!$isAuthRoute) {
                    http_response_code(503);
                    $title = \App\Models\Setting::get('maintenance_title');
                    $message = \App\Models\Setting::get('maintenance_message');
                    $phone = \App\Models\Setting::get('maintenance_phone');
                    require VIEW_PATH . '/errors/maintenance.php';
                    return;
                }
            }

            // 3. Check Module Flags (Shop, Blog, Tickets)
            $isShopRoute = (strpos($uri, '/shop') === 0 || strpos($uri, '/product') === 0 || strpos($uri, '/cart') === 0 || strpos($uri, '/checkout') === 0);
            if ($isShopRoute && \App\Models\Setting::get('module_shop', '1') === '0' && !Auth::isAdmin()) {
                $_SESSION['site_notice'] = 'بخش فروشگاه آنلاین موقتاً طبق صلاح‌دید مدیریت غیرفعال شده است. لطفاً جهت استعلام و سفارش پکیج با تلفن نمایندگی تماس بگیرید.';
                header('Location: ' . url());
                exit;
            }

            $isBlogRoute = (strpos($uri, '/blog') === 0 || strpos($uri, '/articles') === 0);
            if ($isBlogRoute && \App\Models\Setting::get('module_blog', '1') === '0' && !Auth::isAdmin()) {
                header('Location: ' . url());
                exit;
            }

            $isRegisterRoute = (strpos($uri, '/register') === 0);
            if ($isRegisterRoute && \App\Models\Setting::get('module_registration', '1') === '0' && !Auth::isAdmin()) {
                $_SESSION['auth_error'] = 'ثبت‌نام مستقیم کاربران در حال حاضر طبق صلاحدید مدیریت غیرفعال است.';
                header('Location: ' . url('login'));
                exit;
            }
        }

        try {
            foreach ($this->routes as $route) {
                if ($route['method'] !== $requestMethod) {
                    continue;
                }

                // Convert route path to regex
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $uri, $matches)) {
                    $params = array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY);
                    $handler = $route['handler'];

                    if (is_callable($handler)) {
                        call_user_func_array($handler, $params);
                        return;
                    }

                    if (is_array($handler) && count($handler) === 2) {
                        [$class, $method] = $handler;
                        if (class_exists($class)) {
                            $controller = new $class();
                            if (method_exists($controller, $method)) {
                                call_user_func_array([$controller, $method], $params);
                                return;
                            }
                        }
                    }
                }
            }

            // 404 Not Found
            http_response_code(404);
            if (file_exists(VIEW_PATH . '/errors/404.php')) {
                require VIEW_PATH . '/errors/404.php';
            } else {
                echo "<h1 style='text-align:center;'>۴۰۴ - صفحه یافت نشد</h1>";
            }
        } catch (\Throwable $e) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            error_log($e->getMessage() . "\n" . $e->getTraceAsString());
            http_response_code(500);

            if (isset($_GET['debug']) && $_GET['debug'] === 'devshayan' && \App\Core\Auth::isSuperAdmin()) {
                header('Content-Type: text/plain; charset=utf-8');
                echo "Exception: " . $e->getMessage() . "\n";
                echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
                echo "Trace:\n" . $e->getTraceAsString() . "\n";
                return;
            }

            if (file_exists(VIEW_PATH . '/errors/500.php')) {
                require VIEW_PATH . '/errors/500.php';
            } else {
                echo "<h1 style='text-align:center;'>۵۰۰ - خطای سرور</h1>";
            }
        }
    }
}
