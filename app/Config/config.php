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

/**
 * Return crisp Font Awesome font icons (100% Local / Zero CDN)
 */
function icon(string $name, string $class = '', int $size = 0): string {
    static $map = [
        'phone' => 'fa-solid fa-phone',
        'mobile' => 'fa-solid fa-mobile-screen-button',
        'calendar' => 'fa-solid fa-calendar-days',
        'clock' => 'fa-solid fa-clock',
        'eye' => 'fa-solid fa-eye',
        'user' => 'fa-solid fa-user',
        'technician' => 'fa-solid fa-user-gear',
        'search' => 'fa-solid fa-magnifying-glass',
        'shield' => 'fa-solid fa-shield-halved',
        'truck' => 'fa-solid fa-truck-fast',
        'wrench' => 'fa-solid fa-wrench',
        'credit-card' => 'fa-solid fa-credit-card',
        'check' => 'fa-solid fa-check',
        'alert' => 'fa-solid fa-triangle-exclamation',
        'info' => 'fa-solid fa-circle-info',
        'map-pin' => 'fa-solid fa-location-dot',
        'building' => 'fa-solid fa-building',
        'mail' => 'fa-solid fa-envelope',
        'star' => 'fa-solid fa-star',
        'star-empty' => 'fa-regular fa-star',
        'cart' => 'fa-solid fa-cart-shopping',
        'heart' => 'fa-solid fa-heart',
        'ticket' => 'fa-solid fa-ticket',
        'invoice' => 'fa-solid fa-file-invoice-dollar',
        'edit' => 'fa-solid fa-pen-to-square',
        'trash' => 'fa-solid fa-trash-can',
        'flame' => 'fa-solid fa-fire-flame-curved',
        'ruler' => 'fa-solid fa-ruler-combined',
        'gear' => 'fa-solid fa-gear',
        'plus' => 'fa-solid fa-plus',
        'minus' => 'fa-solid fa-minus',
        'arrow-left' => 'fa-solid fa-arrow-left',
        'arrow-right' => 'fa-solid fa-arrow-right',
        'copy' => 'fa-solid fa-copy',
        'map' => 'fa-solid fa-map-location-dot',
        'print' => 'fa-solid fa-print',
        'medal' => 'fa-solid fa-award',
        'filter' => 'fa-solid fa-filter',
        'paper-plane' => 'fa-solid fa-paper-plane',
        'paperclip' => 'fa-solid fa-paperclip',
        'headset' => 'fa-solid fa-headset',
        'comments' => 'fa-solid fa-comments',
        'comment-dots' => 'fa-solid fa-comment-dots',
        'bell' => 'fa-solid fa-bell',
        'circle-check' => 'fa-solid fa-circle-check',
        'circle-xmark' => 'fa-solid fa-circle-xmark',
        'chart' => 'fa-solid fa-chart-line',
        'box' => 'fa-solid fa-box-open',
        'users' => 'fa-solid fa-users',
        'newspaper' => 'fa-solid fa-newspaper',
        'sliders' => 'fa-solid fa-sliders',
        'sign-out' => 'fa-solid fa-right-from-bracket',
        'lock' => 'fa-solid fa-lock',
        'times' => 'fa-solid fa-xmark',
        'close' => 'fa-solid fa-xmark',
        'download' => 'fa-solid fa-download',
        'share' => 'fa-solid fa-share-nodes',
        'bars' => 'fa-solid fa-bars',
        'chevron-left' => 'fa-solid fa-chevron-left',
        'chevron-right' => 'fa-solid fa-chevron-right',
        'chevron-down' => 'fa-solid fa-chevron-down',
        'chevron-up' => 'fa-solid fa-chevron-up',
    ];

    $faClass = $map[$name] ?? 'fa-solid fa-' . $name;
    $style = $size > 0 ? ' style="font-size: ' . $size . 'px;"' : '';
    $extraClass = $class !== '' ? ' ' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') : '';

    return '<i class="' . $faClass . $extraClass . '"' . $style . ' aria-hidden="true"></i>';
}
