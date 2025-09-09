<?php
// Automatically detect the base URL and environment
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

define('BASE_URL', $protocol . $host . $dir);
define('SENDER_EMAIL', '');
define('REPLY_TO_EMAIL', '');

define('ADMIN_PASSWORD', '...');



// Detect if running locally
$localHosts = ['localhost', '127.0.0.1', '::1', 'localhost:8888', 'localhost:8889'];
$isLocal = in_array($host, $localHosts);
define('IS_LOCAL', $isLocal);

// Set DB settings based on environment
if ($isLocal) {
    define('DB_HOST', '');
    define('DB_USER', '');
    define('DB_PASS', '');
    define('DB_NAME', '');
    define('DB_PORT', '');
} else {
    define('DB_HOST', 'production-db-host');
    define('DB_USER', 'production-user');
    define('DB_PASS', 'production-password');
    define('DB_NAME', 'doe_de_check_prod');
    define('DB_PORT', '3306');
}
// Set error reporting based on environment
if ($isLocal) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {    
    error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
    ini_set('display_errors', '0');
}   