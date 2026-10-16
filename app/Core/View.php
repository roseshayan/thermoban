<?php
namespace App\Core;

class View {
    public static function render(string $view, array $data = [], string $layout = 'layouts/main'): void {
        extract($data);
        
        $viewFile = self::resolveViewFile($view);
        
        if (!$viewFile) {
            $expected = VIEW_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $view) . '.php';
            throw new \Exception("View file not found: {$expected}");
        }

        // Start output buffering for view content
        ob_start();
        try {
            require $viewFile;
            $content = ob_get_clean();
        } catch (\Throwable $e) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            throw $e;
        }

        // If layout specified, render inside layout
        if ($layout) {
            $layoutFile = self::resolveViewFile($layout);
            if ($layoutFile) {
                ob_start();
                try {
                    require $layoutFile;
                    echo ob_get_clean();
                    return;
                } catch (\Throwable $e) {
                    while (ob_get_level() > 0) {
                        ob_end_clean();
                    }
                    throw $e;
                }
            }
        }

        echo $content;
    }

    /**
     * Resolves view file with automatic case-insensitive directory support for Linux/cPanel
     */
    public static function resolveViewFile(string $view): ?string {
        $view = trim($view, '/\\');
        $normalized = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $view) . '.php';

        // 1. Direct check with defined VIEW_PATH
        if (defined('VIEW_PATH')) {
            $file = VIEW_PATH . DIRECTORY_SEPARATOR . $normalized;
            if (file_exists($file)) {
                return $file;
            }
        }

        // 2. Check alternative case for Views/views directory under APP_PATH or ROOT_PATH
        $candidateDirs = [];
        if (defined('VIEW_PATH')) $candidateDirs[] = VIEW_PATH;
        if (defined('APP_PATH')) {
            $candidateDirs[] = APP_PATH . DIRECTORY_SEPARATOR . 'Views';
            $candidateDirs[] = APP_PATH . DIRECTORY_SEPARATOR . 'views';
        }
        if (defined('ROOT_PATH')) {
            $candidateDirs[] = ROOT_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views';
            $candidateDirs[] = ROOT_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views';
        }
        $candidateDirs[] = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views';
        $candidateDirs[] = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views';

        foreach (array_unique($candidateDirs) as $baseDir) {
            if (!is_dir($baseDir)) continue;

            $direct = $baseDir . DIRECTORY_SEPARATOR . $normalized;
            if (file_exists($direct)) {
                return $direct;
            }

            // Case-insensitive segment-by-segment check
            $segments = explode('/', str_replace('\\', '/', $view));
            $curr = $baseDir;
            $matched = true;

            foreach ($segments as $idx => $seg) {
                $isLast = ($idx === count($segments) - 1);
                $target = $isLast ? ($seg . '.php') : $seg;

                if (!is_dir($curr)) {
                    $matched = false;
                    break;
                }

                $entries = scandir($curr);
                $found = null;
                foreach ($entries as $e) {
                    if ($e === '.' || $e === '..') continue;
                    if (strcasecmp($e, $target) === 0) {
                        $found = $e;
                        break;
                    }
                }

                if ($found !== null) {
                    $curr = $curr . DIRECTORY_SEPARATOR . $found;
                } else {
                    $matched = false;
                    break;
                }
            }

            if ($matched && file_exists($curr)) {
                return $curr;
            }
        }

        return null;
    }
}
