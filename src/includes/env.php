<?php
// Automatically detect the base URL and environment
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

define('BASE_URL', $protocol . $host . $dir);
define('SENDER_EMAIL', 'noreply@marcelschouwenaar.nl');
define('REPLY_TO_EMAIL', 'info@marcelschouwenaar.nl');


// Detect if running locally
$localHosts = ['localhost', '127.0.0.1', '::1', 'localhost:8888', 'localhost:8889'];
$isLocal = in_array($host, $localHosts);

// Set DB settings based on environment
if ($isLocal) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'root');
    define('DB_NAME', 'doe_de_check_local');
    define('DB_PORT', '8889');
} else {
    define('DB_HOST', 'production-db-host');
    define('DB_USER', 'production-user');
    define('DB_PASS', 'production-password');
    define('DB_NAME', 'doe_de_check_prod');
    define('DB_PORT', '3306');
}

// If running on localhost, set sendmail_path for testing
if ($isLocal) {
    $tmpDir = __DIR__ . '/../tmp';
    $mailFile = $tmpDir . '/mail.txt';

    // Create tmp directory if it doesn't exist
    if (!is_dir($tmpDir)) {
        mkdir($tmpDir, 0777, true);
    }

    // Create mail.txt if it doesn't exist
    if (!file_exists($mailFile)) {
        touch($mailFile);
        chmod($mailFile, 0666);
    }

    ini_set('sendmail_path', 'tee -a ' . $mailFile . ' > /dev/null');


}