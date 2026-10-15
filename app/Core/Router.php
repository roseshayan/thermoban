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
        echo "<!DOCTYPE html><html dir='rtl' lang='fa'><head><meta charset='UTF-8'><title>۴۰۴ - صفحه یافت نشد</title><style>body{font-family:sans-serif;text-align:center;padding:50px;background:#011C39;color:#fff;} a{color:#EF5C16;}</style></head><body><h1>۴۰۴ - صفحه مورد نظر یافت نشد</h1><p><a href='" . url() . "'>بازگشت به صفحه اصلی</a></p></body></html>";
    }
}
