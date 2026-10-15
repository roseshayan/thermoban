<?php
// Prevent direct access
defined('ROOT_PATH') or exit('No direct script access allowed');

// Base paths
define('APP_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'app');
define('VIEW_PATH', APP_PATH . DIRECTORY_SEPARATOR . 'Views');
define('PUBLIC_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'public');

// Database configuration
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'thermoban_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Site info
define('SITE_NAME', 'ترموبان | مرجع تخصصی نصب، تعمیر و فروش پکیج');
define('SITE_PHONE_1', '۰۹۱۲-۳۴۵-۶۷۸۹');
define('SITE_PHONE_2', '۰۲۱-۱۲۳۴۵۶۷۸');
define('SITE_ADDRESS', 'تهران، خیابان کمالی، خیابان غفاری، پلاک ۱۷۵');
define('SITE_EMAIL', 'info@thermoban.ir');

/**
 * Detect base URL dynamically based on current host & script path
 */
function get_base_url(): string {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $dir = trim(str_replace('\\', '/', dirname($script)), '/');
    
    // If running in /thermoban/public or /thermoban
    if ($dir === '' || $dir === '.') {
        return $protocol . $host . '/';
    }
    
    // If public is in the script folder
    if (basename($dir) === 'public') {
        $parent = dirname($dir);
        if ($parent === '' || $parent === '.') {
            return $protocol . $host . '/' . $dir . '/';
        }
        return $protocol . $host . '/' . $parent . '/public/';
    }
    
    return $protocol . $host . '/' . $dir . '/';
}

define('BASE_URL', rtrim(get_base_url(), '/') . '/');

/**
 * Return absolute URL for a given path
 */
function url(string $path = ''): string {
    return BASE_URL . ltrim($path, '/');
}

/**
 * Return asset URL
 */
function asset(string $path = ''): string {
    // If base URL doesn't contain /public and we are accessed directly from project root
    $cleanPath = ltrim($path, '/');
    
    // Check if assets folder is directly inside public
    if (strpos(BASE_URL, '/public/') !== false) {
        return BASE_URL . 'assets/' . $cleanPath;
    }
    
    // If accessed through root folder in Laragon (e.g. /thermoban/)
    return BASE_URL . 'public/assets/' . $cleanPath;
}

/**
 * Escape HTML
 */
function e(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
