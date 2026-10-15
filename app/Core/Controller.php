<?php
namespace App\Core;

abstract class Controller {
    protected function view(string $view, array $data = [], string $layout = 'layouts/main'): void {
        View::render($view, $data, $layout);
    }

    protected function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    protected function redirect(string $path): void {
        header('Location: ' . url($path));
        exit;
    }
}
