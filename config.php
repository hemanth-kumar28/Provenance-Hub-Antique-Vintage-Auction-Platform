<?php
// Global Constants
define('DB_HOST', 'localhost');
define('DB_NAME', 'antique_auction_hub');
define('DB_USER', 'root'); 
define('DB_PASS', '');

// Configure error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Explicitly set default timezone as required
date_default_timezone_set('UTC');

// Composer Autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Define application paths
define('BASE_PATH', __DIR__);
define('LOG_PATH', BASE_PATH . '/logs/log.txt');
define('UPLOAD_PATH', BASE_PATH . '/public/uploads/');

// Create necessary directories if they don't exist
if (!is_dir(dirname(LOG_PATH))) {
    mkdir(dirname(LOG_PATH), 0755, true);
}
if (!is_dir(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
}

// Session Management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Define dynamic base URL for XAMPP subfolders
$baseDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim($baseDir, '/') . '/');
