<?php
/**
 * Thermoban - Front Controller
 */

// Define root path
define('ROOT_PATH', dirname(__DIR__));

// Start secure session if not started
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    session_start();
}

// Load configuration
require_once ROOT_PATH . '/app/Config/config.php';

// Run Application
require_once ROOT_PATH . '/app/Core/Application.php';
\App\Core\Application::run();
