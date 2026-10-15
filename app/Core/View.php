<?php
namespace App\Core;

class View {
    public static function render(string $view, array $data = [], string $layout = 'layouts/main'): void {
        extract($data);
        
        $viewFile = VIEW_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $view) . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$viewFile}");
        }

        // Start output buffering for view content
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // If layout specified, render inside layout
        if ($layout) {
            $layoutFile = VIEW_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $layout) . '.php';
            if (file_exists($layoutFile)) {
                require $layoutFile;
                return;
            }
        }

        echo $content;
    }
}
